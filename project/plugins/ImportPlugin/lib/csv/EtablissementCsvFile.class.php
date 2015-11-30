<?php 

class EtablissementCsvFile extends CsvFile 
{

  const CSV_ID = 0;
  const CSV_ID_SOCIETE = 1;
  const CSV_TYPE = 2;
  const CSV_NOM = 3;
  const CSV_STATUT = 4;
  const CSV_REGION = 5;
  const CSV_CVI = 6;
  const CSV_NO_ACCISES = 7;
  const CSV_CARTE_PRO = 8;
  const CSV_RECETTE_LOCALE = 9;
  const CSV_ADRESSE = 10;
  const CSV_ADRESSE_COMPLEMENTAIRE_1 = 11;
  const CSV_ADRESSE_COMPLEMENTAIRE_2 = 12;
  const CSV_ADRESSE_COMPLEMENTAIRE_3 = 13;
  const CSV_CODE_POSTAL = 14;
  const CSV_COMMUNE = 15;
  const CSV_CEDEX = 16;
  const CSV_PAYS = 17;
  const CSV_EMAIL = 18;
  const CSV_TEL_BUREAU = 19;
  const CSV_TEL_PERSO = 20;
  const CSV_MOBILE = 21;
  const CSV_FAX = 22;
  const CSV_WEB = 23;
  const CSV_COMMENTAIRE = 24;

  private function verifyCsvLine($line) {
        if (!preg_match('/[0-9]+/', $line[self::CSV_ID])) {

            throw new Exception(sprintf('ID invalide : %s', $line[self::CSV_ID]));
        }
    }

  public function importEtablissements() {
    $this->errors = array();
    $etablissements = array();
    $csvs = $this->getCsv();
      foreach ($csvs as $line) {
        try {
      	  $this->verifyCsvLine($line);

          /*$famille = $this->convertTypeInFamille($line[self::CSVPAR_TYPE_PARTENAIRE]);
          if (!$famille) {
	          echo "Etablissement ERROR: ".$line[self::CSVPAR_CODE_CLIENT].": Pas de Famille connue\n";
            continue;
          }*/

          $id = sprintf("%06d01", $line[self::CSV_ID]);
          $id_societe = sprintf("%06d", $line[self::CSV_ID]);


      	  $e = EtablissementClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
          
          if ($e) {
	          echo "WARNING: Etablissement ".$id." existe\n";
	          continue;
          }

          $s = SocieteClient::getInstance()->find($id_societe);

          if (!$s) {
            echo "WARNING: Societe ".$line[$id_societe]." n'existe pas\n";
            continue;
          }
	
          /*$id = sprintf("%06d", $line[self::CSVPAR_CODE_CLIENT]).sprintf("%02d", $chai);
  	       $e = EtablissementClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
          if ($e) {
        	  echo "WARNING: Etablissement ".$id." existe\n";
        	  continue;
          }*/

          $e = EtablissementClient::getInstance()->createEtablissementFromSociete($s);

          $e->identifiant = $id;
          $e->constructId();

        	$e->nom = $line[self::CSV_NOM];
          $e->cvi = (isset($line[self::CSV_CVI])) ? $line[self::CSV_CVI] : null;
          $e->no_accises = (isset($line[self::CSV_NO_ACCISES])) ? $line[self::CSV_NO_ACCISES] : null;
          $e->carte_pro = (isset($line[self::CSV_CARTE_PRO])) ? $line[self::CSV_CARTE_PRO] : null;
          $e->interpro = 'INTERPRO-declaration';
          $e->statut = $line[self::CSV_STATUT];
          $e->region = (isset($line[self::CSV_REGION])) ? $line[self::CSV_REGION] : null;
          $e->compte = $s->compte_societe;
        	
          $e->save();

          echo $e->_id . "\n";
      
      }catch(Exception $e) {
        echo $e->getMessage()."\n";
      }
    }
    return $etablissements;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function convertTypeInFamille($type) {

    $types_familles = array(
      self::CSV_TYPE_PARTENAIRE_VITICULTEUR => EtablissementFamilles::FAMILLE_PRODUCTEUR,
      self::CSV_TYPE_PARTENAIRE_NEGOCE => EtablissementFamilles::FAMILLE_NEGOCIANT,
      self::CSV_TYPE_PARTENAIRE_COURTIER => EtablissementFamilles::FAMILLE_COURTIER,
    );

    if (array_key_exists($type, $types_familles)) {
      
      return $types_familles[$type];
    }

    return null;
  }

  public function getSousFamilleDefaut($famille) {
    if($famille == EtablissementFamilles::FAMILLE_PRODUCTEUR) {

        return EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
    }

    if($famille == EtablissementFamilles::FAMILLE_NEGOCIANT) {
        
        return EtablissementFamilles::SOUS_FAMILLE_REGIONAL;
    }

    if($famille == EtablissementFamilles::FAMILLE_COURTIER) {
        
        return '';
    }
  }
}
