<?php 

class CompteCsvFile extends CsvFile 
{

const CSV_COMPTE_Dossier = 1;
const CSV_COMPTE_CODE_CONTACT = 0;
const CSV_COMPTE_NOM_CONTACT = 2;
const CSV_COMPTE_NOM_REDIOT = 3;
const CSV_COMPTE_ADRESSE1 = 4;
const CSV_COMPTE_ADRESSE2 = 5;
const CSV_COMPTE_ADRESSE3 = 6;
const CSV_COMPTE_CODE_POSTAL = 7;
const CSV_COMPTE_COMMUNE = 8;
const CSV_COMPTE_CODE_PAYS = 9;
const CSV_COMPTE_TELEPHONE_PRO = 10;
const CSV_COMPTE_TELEPHONE_PERSO = 11;
const CSV_COMPTE_PORTABLE_PRO = 12;
const CSV_COMPTE_PORTABLE_PERSO = 13;
const CSV_COMPTE_FAX = 14;
const CSV_COMPTE_EMAIL_PRO = 15;
const CSV_COMPTE_EMAIL_PERSO = 16;
const CSV_COMPTE_WEB = 17;
//libre 18
//libre 19
//libre 20
const CSV_COMPTE_CODE_PARTENAIRE = 22;

  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]+/', $line[self::CSV_COMPTE_CODE_CONTACT])) {

      throw new Exception(sprintf('Numero de dossier invalide : %s', $line[self::CSV_COMPTE_CODE_CONTACT]));
    }
  }

  private function generateIdentifiant($id) {
	for ($i = 1 ;; $i++) {
		$newid = sprintf("%06d%02d", $id, $i);
		if (!isset($this->registeredid[$newid]))
			break;
        }
	$this->registeredid[$newid] = $newid;
	return $newid; 
  }

  public function importComptes () {
    $this->errors = array();
    $societes = array();
    $csvs = $this->getCsv();
    try {
      foreach ($csvs as $line) {
      	$this->verifyCsvLine($line);

	$compteidentifiant = $this->generateIdentifiant($line[self::CSV_COMPTE_CODE_PARTENAIRE]);

      	$c = CompteClient::getInstance()->find("COMPTE-".$compteidentifiant, acCouchdbClient::HYDRATE_JSON);
        if ($c) {
          acCouchdbManager::getClient()->deleteDoc($c);
        }
	try{
      	$c = new Compte();
        $c->identifiant = $compteidentifiant;
        $c->nom_a_afficher = $line[self::CSV_COMPTE_NOM_CONTACT];
	$c->id_societe = sprintf("SOCIETE-%06d", $line[self::CSV_COMPTE_CODE_PARTENAIRE]);
	$c->adresse = preg_replace('/,/', '', $line[self::CSV_COMPTE_ADRESSE1]);
        if(preg_match('/[a-z]/i', $line[self::CSV_COMPTE_ADRESSE2])) {
        $c->adresse .= ", ".preg_replace('/,/', '', $line[self::CSV_COMPTE_ADRESSE2]);
        if(preg_match('/[a-z]/i', $line[self::CSV_COMPTE_ADRESSE3])) {
        $c->adresse .= ", ".preg_replace('/,/', '', $line[self::CSV_COMPTE_ADRESSE3]);
        }}
        $c->code_postal = $line[self::CSV_COMPTE_CODE_POSTAL];
        $c->commune = $line[self::CSV_COMPTE_COMMUNE];
        $c->pays = $line[self::CSV_COMPTE_CODE_PAYS];
	$c->email = preg_replace('/([^ ]*@[^ ]*) .*/', '\1', $line[self::CSV_COMPTE_EMAIL_PRO].' '.$line[self::CSV_COMPTE_EMAIL_PERSO]);
	$c->fax = $line[self::CSV_COMPTE_FAX];
	$c->telephone_bureau =  $line[self::CSV_COMPTE_TELEPHONE_PRO];
        $c->telephone_perso =  $line[self::CSV_COMPTE_TELEPHONE_PERSO];
        $c->telephone_mobile =  $line[self::CSV_COMPTE_PORTABLE_PRO];
	if (!$c->telephone_mobile) {
		$c->telephone_mobile =  $line[self::CSV_COMPTE_PORTABLE_PERSO];
	}
      	$c->save();
	}catch(sfException $e) {
		echo "WARNING: $e \n";
	}
      }
    }catch(Execption $e) {
      $this->error[] = $e->getMessage();
    }
    return $societes;
  }

  public function getErrors() {
    return $this->errors;
  }


}
