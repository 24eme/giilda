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
  const CSV_INSEE = 16;
  const CSV_CEDEX = 17;
  const CSV_PAYS = 18;
  const CSV_EMAIL = 19;
  const CSV_TEL_BUREAU = 20;
  const CSV_TEL_PERSO = 21;
  const CSV_MOBILE = 22;
  const CSV_FAX = 23;
  const CSV_WEB = 24;
  const CSV_COMMENTAIRE = 25;

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

          $id = $line[self::CSV_ID];
          $id_societe = $line[self::CSV_ID_SOCIETE];

          
      	  $e = EtablissementClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
          
          if ($e) {
	          echo "ERROR: Etablissement ".$id." existe\n";
	          continue;
          }

          $s = SocieteClient::getInstance()->find($id_societe);

          if (!$s) {
            echo "WARNING: Societe ".$id_societe." n'existe pas\n";
            continue;
          }

          $e = EtablissementClient::getInstance()->createEtablissementFromSociete($s,$line[self::CSV_TYPE]);

          $e->identifiant = $id;
          $e->constructId();

          $e->famille=$line[self::CSV_TYPE];

          if(!array_key_exists($e->famille, EtablissementFamilles::getFamilles())) {

              throw new sfException(sprintf("La famille %s n'est pas connue", $e->famille));
          }

        	$e->nom = $this->getNom($line, $s, $e);
          $e->cvi = (isset($line[self::CSV_CVI])) ? str_replace(" ", "", $line[self::CSV_CVI]) : null;
          $e->no_accises = (isset($line[self::CSV_NO_ACCISES])) ? str_replace(" ", "", $line[self::CSV_NO_ACCISES]) : null;
          $e->carte_pro = (isset($line[self::CSV_CARTE_PRO])) ? str_replace(" ", "", $line[self::CSV_CARTE_PRO]) : null;
          $e->interpro = 'INTERPRO-declaration';
          $e->statut = ($s->statut == SocieteClient::STATUT_SUSPENDU) ? $s->statut : $line[self::CSV_STATUT];
          $e->region = (isset($line[self::CSV_REGION])) ? $line[self::CSV_REGION] : null;
          $e->compte = $s->compte_societe;
          $e->synchroFromCompte();

          //$this->compareAdresse($line, $s, $e);
        	
          $e->save();
      }catch(Exception $e) {
        echo $e->getMessage()."\n";
      }
    }
    
    return $etablissements;
  }

  protected function compareAdresse($line, $s, $e) {
      $adresseS = preg_replace("/[ ]+/", " ", $s->siege->adresse." ".$s->siege->code_postal." ".$s->siege->commune);
      $adresseE = preg_replace("/[ ]+/", " ", trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE]))." ".$line[self::CSV_CODE_POSTAL]." ".$line[self::CSV_COMMUNE]);

      if(KeyInflector::slugify($adresseE) != KeyInflector::slugify($adresseS)) {

          return ;
      }

      echo $adresseS . " / " . $adresseE . "\n"; 
  }

  protected function getNom($line, $s, $e) {
      if(trim($line[self::CSV_NOM])) {
          $nomE = preg_replace("/[ ]+/", " ", strtoupper(preg_replace('/[\._()-]+/', " ", $line[self::CSV_NOM])));
          $nomS = preg_replace("/[ ]+/", " ", strtoupper(preg_replace('/[\._()-]+/', " ", $s->raison_sociale)));
          $wordsE = explode(" ", $nomE);
          $wordsS = explode(" ", $nomS);
          $score = 0;
          foreach($wordsE as $wordE) {
            if(strlen($wordE) <= 3) {
              continue;
            }
            $minScore = 99;
            $wordFind = null;
            foreach($wordsS as $key => $wordS) {
                if(strlen($wordS) <= 3) {
                  continue;
                }
                $percent = levenshtein(KeyInflector::slugify($wordE), KeyInflector::slugify($wordS));
                if($percent < $minScore) {
                  $minScore = $percent;
                }
            }
            $score += $minScore;
          }
          $scoreGlobal = levenshtein($nomE, $nomS);

          if($scoreGlobal < $score) {
            $score = $scoreGlobal;
          }
          if($score <= 1) {

            return $s->raison_sociale;
          } elseif($score <= 4) {

            $e->addCommentaire("Nom provenant de l'import: ".$line[self::CSV_NOM]);

            return $s->raison_sociale;
          } else {

            return strtoupper($line[self::CSV_NOM]);
          }
      }

      return $s->raison_sociale;
  }

  public function getErrors() {
    return $this->errors;
  }
}
