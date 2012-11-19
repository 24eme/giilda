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
        $s->raison_sociale = $line[self::CSV_PARTENAIRE_NOM];
        $s->code_postal = $line[self::CSV_PARTENAIRE_CODEPOSTAL];
        $s->commune = $line[self::CSV_PARTENAIRE_COMMUNE];
	$s->interpro = 'INTERPRO-inter-loire';
	$s->telephone = "02 XX YY ZZ QQ";
	$s->SIRET = '49? ??? ???';
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