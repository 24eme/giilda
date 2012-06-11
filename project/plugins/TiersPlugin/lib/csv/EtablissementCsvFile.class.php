<?php 

class EtablissementCsvFile extends CsvFile 
{
  const CSV_DOSSIER = 1;
  const CSV_CODE_PARTENAIRE = 0;
  const CSV_NOM_DU_PARTENAIRE = 2;
  const CSV_TYPE_PARTENAIRE = 4;
  const CSV_PARTENAIRE_COMMUNE = 14;
  const CSV_PARTENAIRE_CODE_POSTAL = 13;
  const CSV_CVI = 25;
  
  const CSV_TYPE_PARTENAIRE_VITICULTEUR = 'V';
  const CSV_TYPE_PARTENAIRE_NEGOCE = 'N';
  const CSV_TYPE_PARTENAIRE_COURTIER = 'C';

  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]+/', $line[self::CSV_CODE_PARTENAIRE])) {

      throw new Exception(sprintf('Numero de dossier invalide : %s', $line[self::CSV_CODE_PARTENAIRE]));
    }
  }

  public function importEtablissements() {
    $this->errors = array();
    $etablissements = array();
    $csvs = $this->getCsv();
    try {
      foreach ($csvs as $line) {
      	$this->verifyCsvLine($line);

        $famille = $this->convertTypeInFamille($line[self::CSV_TYPE_PARTENAIRE]);
        if (!$famille) {
          continue;
        }

      	$e = EtablissementClient::getInstance()->findByIdentifiant($line[self::CSV_CODE_PARTENAIRE], acCouchdbClient::HYDRATE_JSON);
        if ($e) {
          acCouchdbManager::getClient()->deleteDoc($e);
        }

      	$e = new Etablissement();
        $e->identifiant = $line[self::CSV_CODE_PARTENAIRE];
        $e->nom = $line[self::CSV_NOM_DU_PARTENAIRE];
        $e->cvi = isset($line[self::CSV_CVI]) ? $line[self::CSV_CVI] : null;
        $e->commune = $line[self::CSV_PARTENAIRE_COMMUNE];
        $e->code_postal = $line[self::CSV_PARTENAIRE_CODE_POSTAL];
        $e->famille = $famille;
        

      	$etablissements[$e->identifiant] = $e;
      }
    }catch(Execption $e) {
      $this->error[] = $e->getMessage();
    }
    return $etablissements;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function convertTypeInFamille($type) {

    $types_familles = array(
      self::CSV_TYPE_PARTENAIRE_VITICULTEUR => EtablissementClient::FAMILLE_VITICULTEUR,
      self::CSV_TYPE_PARTENAIRE_NEGOCE => EtablissementClient::FAMILLE_NEGOCE,
      self::CSV_TYPE_PARTENAIRE_COURTIER => EtablissementClient::FAMILLE_COURTIER,
    );

    if (array_key_exists($type, $types_familles)) {
      
      return $types_familles[$type];
    }

    return null;
  }
}