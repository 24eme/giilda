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
const CSV_COMPTE_FONCTION = 23;


const COMPTE_FONCTION_PARTENAIRE = 'PARTENAIRE';

  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]+/', $line[self::CSV_COMPTE_CODE_CONTACT])) {

      throw new sfException(sprintf('Numero de dossier invalide : %s', $line[self::CSV_COMPTE_CODE_CONTACT]));
    }
    if (!isset($line[self::CSV_COMPTE_CODE_PARTENAIRE])) {
      throw new sfException('Wrong format for line '.$this->line_nb);
    }
  }

  private function generateIdentifiant($id) {
	for ($i = 1 ;; $i++) {
		$newid = sprintf("%06d%02d", $id, $i);
		if (!isset($this->registeredid[$newid]) && !CompteClient::getInstance()->find("COMPTE-".$newid, acCouchdbClient::HYDRATE_JSON))
			break;
        }
	$this->registeredid[$newid] = $newid;
	return $newid; 
  }

  public function importComptes () {
    $this->errors = array();
    $societes = array();
    $csvs = $this->getCsv();
    $this->line_nb = 0;
    try {
      foreach ($csvs as $line) {
        try{
	$this->line_nb++;
      	$this->verifyCsvLine($line);

	$compteidentifiant = $this->generateIdentifiant($line[self::CSV_COMPTE_CODE_PARTENAIRE]);

      	$c = CompteClient::getInstance()->find("COMPTE-".$compteidentifiant, acCouchdbClient::HYDRATE_JSON);
        if ($c) {
          acCouchdbManager::getClient()->deleteDoc($c);
        }
        $societe = SocieteClient::getInstance()->find(sprintf("SOCIETE-%06d", $line[self::CSV_COMPTE_CODE_PARTENAIRE]), acCouchdbClient::HYDRATE_JSON);
        if(!$societe) {
            throw new sfException(sprintf("Societe introuvable '%s'", sprintf("SOCIETE-%06d", $line[self::CSV_COMPTE_CODE_PARTENAIRE])));
        }
        
      	$c = new Compte();
        $c->identifiant = $compteidentifiant;
        $c->interpro = 'INTERPRO-inter-loire';
        $c->nom_a_afficher = $line[self::CSV_COMPTE_NOM_CONTACT];
	      $c->id_societe = $societe->_id;
        $c->statut = $societe->statut;
	      $c->adresse = preg_replace('/,/', '', $line[self::CSV_COMPTE_ADRESSE1]);
        if(preg_match('/[a-z]/i', $line[self::CSV_COMPTE_ADRESSE2])) {
        $c->add('adresse_complementaire',preg_replace('/,/', '', $line[self::CSV_COMPTE_ADRESSE2]));
        if(preg_match('/[a-z]/i', $line[self::CSV_COMPTE_ADRESSE3])) {
        $c->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSV_COMPTE_ADRESSE3]);
        }}
        $c->code_postal = $line[self::CSV_COMPTE_CODE_POSTAL];
        $c->commune = $line[self::CSV_COMPTE_COMMUNE];
        $c->pays = $line[self::CSV_COMPTE_CODE_PAYS];
      	$c->email = preg_replace('/([^ ]*@[^ ]*) .*/', '\1', $line[self::CSV_COMPTE_EMAIL_PRO].' '.$line[self::CSV_COMPTE_EMAIL_PERSO]);
      	$c->fax = $line[self::CSV_COMPTE_FAX];
        $c->telephone_perso =  $line[self::CSV_COMPTE_TELEPHONE_PERSO];
      	$c->telephone_bureau = $line[self::CSV_COMPTE_TELEPHONE_PRO];
        $c->telephone_mobile =  $line[self::CSV_COMPTE_PORTABLE_PRO];
      	if (!$c->telephone_mobile) {
      		$c->telephone_mobile =  $line[self::CSV_COMPTE_PORTABLE_PERSO];
      	}
      	if (!$c->telephone_perso) {
      		$c->telephone_perso =  $line[self::CSV_COMPTE_PORTABLE_PERSO];
        }

        if($line[self::CSV_COMPTE_FONCTION] == self::COMPTE_FONCTION_PARTENAIRE) {
          $compte = CompteClient::getInstance()->find($societe->compte_societe);
          $compte->fax = $c->fax;
          $compte->email = $c->email;
          $compte->telephone_perso = $c->telephone_perso;
          $compte->telephone_mobile = $c->telephone_mobile;
          $compte->telephone_bureau = $c->telephone_bureau;
          $compte->save();
          continue;
        }

        $c->save();

      	}catch(sfException $e) {
      		echo sprintf("WARNING: %s \n", $e->getMessage());
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
