<?php 

class SocieteCsvFile extends CsvFile 
{
  const CSV_PARTENAIRE_DOSSIER = 0;
  const CSV_PARTENAIRE_CODE = 1;
  const CSV_PARTENAIRE_NOM = 2;
  const CSV_PARTENAIRE_NOM_REDUIT = 3;
  const CSV_PARTENAIRE_TYPE = 4;
  const CSV_PARTENAIRE_COOPGROUP = 5;
  const CSV_PARTENAIRE_COURTIER = 6;
  const CSV_PARTENAIRE_RECETTE_LOCALE = 7;
  const CSV_PARTENAIRE_ENACTIVITE = 8;
  const CSV_PARTENAIRE_ADRESSE1 = 9;
  const CSV_PARTENAIRE_ADRESSE2 = 10;
  const CSV_PARTENAIRE_ADRESSE3 = 11;
  const CSV_PARTENAIRE_ADRESSE4 = 12;
  const CSV_PARTENAIRE_CODEPOSTAL = 13;
  const CSV_PARTENAIRE_COMMUNE = 14;
  const CSV_PARTENAIRE_PAYS = 15;
  const CSV_PARTENAIRE_CREATION_DATE = 16;
  const CSV_PARTENAIRE_MODIFICATION_DATE = 17;
  const CSV_PARTENAIRE_RELANCE_DREV = 18;
  const CSV_PARTENAIRE_JOURNAL_ABONNE = 19;
  const CSV_PARTENAIRE_JOURNAL_NBEXEMPLAIRES = 20;
  const CSV_PARTENAIRE_EXPLOITANTBAILLEUR = 21;
  const CSV_PARTENAIRE_CHAIPRINICPAL = 22;
  const CSV_PARTENAIRE_ENSEIGNE = 23;
  const CSV_PARTENAIRE_REGIONVITI = 24;
  const CSV_PARTENAIRE_CONTACT_CONTRATS = 25;


  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]+/', $line[self::CSV_PARTENAIRE_CODE])) {

      throw new Exception(sprintf('Numero de dossier invalide : %s', $line[self::CSV_PARTENAIRE_CODE]));
    }
  }

  public function importSocietes () {
    $this->errors = array();
    $societes = array();
    $csvs = $this->getCsv();
    try {
      foreach ($csvs as $line) {
      	$this->verifyCsvLine($line);

      	$s = SocieteClient::getInstance()->find($line[self::CSV_PARTENAIRE_CODE], acCouchdbClient::HYDRATE_JSON);
        if ($s) {
          acCouchdbManager::getClient()->deleteDoc($s);
        }

      	$s = new Societe();
        $s->identifiant = $line[self::CSV_PARTENAIRE_CODE];
        $s->raison_sociale = $line[self::CSV_PARTENAIRE_NOM_REDUIT];
	$s->raison_sociale_abregee = $line[self::CSV_PARTENAIRE_NOM];
	$s->siege->adresse = preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE1]);
        if(preg_match('/[a-z]/i', $line[self::CSV_PARTENAIRE_ADRESSE2])) {
        $s->siege->adresse .= ", ".preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE2]);
        if(preg_match('/[a-z]/i', $line[self::CSV_PARTENAIRE_ADRESSE3])) {
        $s->siege->adresse .= ", ".preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE3]);
        if(preg_match('/[a-z]/i', $line[self::CSV_PARTENAIRE_ADRESSE4])) {
        $s->siege->adresse .= ", ".preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE4]);
        }}}
        $s->siege->code_postal = $line[self::CSV_PARTENAIRE_CODEPOSTAL];
        $s->siege->commune = $line[self::CSV_PARTENAIRE_COMMUNE];
	$s->interpro = 'INTERPRO-inter-loire';
	//Incohérent mais ce champ signifie en réalisé suspendu
        if ($line[self::CSV_PARTENAIRE_ENACTIVITE] == 'O') {
                $s->statut = SocieteClient::STATUT_SUSPENDU;
        }else{
                $s->statut = Etablissement::STATUT_ACTIF;
        }
	if ($line[self::CSV_PARTENAIRE_TYPE] == 'N') {
		$s->type_societe = SocieteClient::SUB_TYPE_NEGOCIANT;
		$s->numero_compte_client = sprintf("04%06d", $line[self::CSV_PARTENAIRE_CODE]);
        }else if ($line[self::CSV_PARTENAIRE_TYPE] == 'C') {
		$s->type_societe = SocieteClient::SUB_TYPE_COURTIER;
	}else if ($line[self::CSV_PARTENAIRE_TYPE] == 'V') {
		$s->type_societe = SocieteClient::SUB_TYPE_VITICULTEUR;
		$s->numero_compte_client = sprintf("02%06d", $line[self::CSV_PARTENAIRE_CODE]);
	}else if ($line[self::CSV_PARTENAIRE_TYPE] == 'R') {
                $s->type_societe = SocieteClient::SUB_TYPE_DOUANE;
	}
	if ($line[self::CSV_PARTENAIRE_ENSEIGNE])
		$s->enseignes->add(null, $line[self::CSV_PARTENAIRE_ENSEIGNE]);


      	$s->save();
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
