<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMImportCsvEdi
 *
 * @author mathurin
 */
class DAEImportCsvEdi extends DAECsvEdi {

    protected $configuration_date = null;
    protected $configuration = null;
    protected $all_produits = null;
    protected $csvDoc = null;
    protected $file = null;
    protected $identifiant = null;

      public function __construct($file = null, $identifiant ,$periode) {
            $this->identifiant = $identifiant;
            $this->file = $file;
            if(is_null($this->csvDoc)) {
                $this->csvDoc = CSVDAEClient::getInstance()->createOrFindDocFromDAES($file, $identifiant ,$periode);
            }
            parent::__construct($file, array());
        }

    public function getDaes(){
          return $this->daes;
      }

      public function getCsvDoc() {

            return $this->csvDoc;
        }

        protected function getConfigProduit($date) {
            if($date != $this->configuration_date){
                $this->configuration = ConfigurationClient::getCurrent($date);
                $this->all_produits = $this->configuration->formatProduits($date, "%format_libelle%", array());
            }
            return $this->all_produits;
        }

        public function getCsvArrayErreurs(){
          $csvErreurs = array();
          $csvErreurs[] = array("#Niveau erreur","Numéro ligne de l'erreur","Parametre en erreur ","Diagnostic");
          if($this->getCsvDoc()->hasErreurs()){
            $erreursRows = $this->getCsvDoc()->getErreurs();
            foreach ($erreursRows as $erreur) {
              $erreurLevel = ($erreur->exist('level'))? CSVDAEClient::$levelErrorsLibelle[$erreur->level] : "";
              $csvErreurs[] = array($erreurLevel,"".$erreur->num_ligne,"".$erreur->csv_erreur,$erreur->diagnostic);
            }
          }
          return $csvErreurs;
        }


        public function getDocRows() {
            if($this->file){
                return $this->getCsv();
            }
            if($this->csvDoc->hasCsvAttachement()){
                $csvFile = new CsvFile($this->csvDoc->getAttachmentUri($this->csvDoc->getFileName()));
                return $csvFile->getCsv();
            }

            return array();
        }

    /**
     * CHECK DU CSV
     */
    public function checkCSV() {
        $this->csvDoc->clearErreurs();
        $this->checkCSVIntegrity();
        if ($this->csvDoc->hasErreurs()) {
            $this->csvDoc->setStatut(self::STATUT_ERREUR);
            $this->csvDoc->save();
            return;
        }
        // Check les lignes
        $this->checkRowsFromCSV();
        if ($this->csvDoc->hasErreurs()) {
            $this->csvDoc->setStatut(self::STATUT_ERREUR);
            $this->csvDoc->save();
            return false;
        }
        $this->csvDoc->setStatut(self::STATUT_VALIDE);
        $this->csvDoc->save();
        return true;
    }

    /**
     * IMPORT DEPUIS LE CSV
     */
    public function importCSV() {
        return $this->importDaesFromCSV();
    }


    private function checkCSVIntegrity() {
        $ligne_num = 1;
        foreach ($this->getDocRows() as $csvRow) {
            if(count($csvRow) != 30){
              $this->csvDoc->addErreur($this->createWrongFormatFieldCountError($ligne_num, $csvRow));
              $ligne_num++;
              continue;
            }
            if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', KeyInflector::slugify($csvRow[self::CSV_DATE_COMMERCIALISATION]))) {
                $this->csvDoc->addErreur($this->createWrongFormatDateCommercialisationError($ligne_num, $csvRow));
                $ligne_num++;
                continue;
            }
            //Existance etablissement
            $identifiantCsv = KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]);
            $etbCsv = EtablissementClient::getInstance()->findByIdentifiant($identifiantCsv);
            if(!$etbCsv){
                $this->csvDoc->addErreur($this->createDifferentEtbError($ligne_num, $csvRow));
                $ligne_num++;
                continue;
            }
            if($this->identifiant != $identifiantCsv){
                $this->csvDoc->addErreur($this->createDifferentEtbError($ligne_num, $csvRow));
                $ligne_num++;
                continue;
            }

            $ligne_num++;
        }
    }

    private function checkRowsFromCSV() {
        return $this->importDaesFromCSV(true);
    }

    private function importDaesFromCSV($just_check = false) {
        $num_ligne = 1;
        // analyse début;
        foreach ($this->getDocRows() as $csvRow) {

            $this->verifyEtablissementInfo($csvRow);
            $founded_produit = $this->matchProduit($csvRow);
         	if (!$founded_produit) {
                      $this->csvDoc->addErreur($this->productNotFoundError($num_ligne, $csvRow));
                      $num_ligne++;
                      continue;
            }
            $this->checkCommercialisationFields($csvRow);

            if (!$just_check) {
                $dae = $this->createDae($csvRow,$founded_produit);
                $dae->save();
            }

            $num_ligne++;
        }
    }
    private function verifyEtablissementInfo(){

    }


    public function matchProduit($csvRow){
        $founded_produit = null;
        $csvLibelleProductArray = $this->buildLibellesArrayWithRow($csvRow, true);
        $csvLibelleProductComplet = $this->slugifyProduitArrayOrString($csvLibelleProductArray);

        $keys_libelle = preg_replace("/[ ]+/", " ", sprintf("%s %s %s %s %s %s %s", $csvRow[self::CSV_PRODUIT_CERTIFICATION], $csvRow[self::CSV_PRODUIT_GENRE], $csvRow[self::CSV_PRODUIT_APPELLATION], $csvRow[self::CSV_PRODUIT_MENTION], $csvRow[self::CSV_PRODUIT_LIEU], $csvRow[self::CSV_PRODUIT_COULEUR], $csvRow[self::CSV_PRODUIT_CEPAGE]));

        $all_produits_conf = $this->getConfigProduit(KeyInflector::slugify($csvRow[self::CSV_DATE_COMMERCIALISATION]));

        if(!$founded_produit && ($keys_libelle != '      ')) {
            return $this->configuration->identifyProductByLibelle($keys_libelle);
        }
        if (!$founded_produit) {
            foreach ($all_produits_conf as $produit) {
                if ($founded_produit) {
                    break;
                }
                $produitConfLibelleAOC = $this->slugifyProduitConf($produit);
                $produitConfLibelleAOP = $this->slugifyProduitConf($produit,true);
                $libelleCompletConfAOC = $this->slugifyProduitArrayOrString($produitConfLibelleAOC);
                $libelleCompletConfAOP = $this->slugifyProduitArrayOrString($produitConfLibelleAOP);
                $libelleCompletEnCsv = $this->slugifyProduitArrayOrString($csvRow[self::CSV_PRODUIT_LIBELLE_PRODUIT]);
                var_dump("here"); exit;

                $isEmptyArray = $this->isEmptyArray($csvLibelleProductArray);

                if ($isEmptyArray){
                  if(($libelleCompletConfAOC != $csvLibelleProductComplet) && ($libelleCompletConfAOP != $csvLibelleProductComplet)
                  && ($libelleCompletConfAOC != $libelleCompletEnCsv) && ($libelleCompletConfAOP != $libelleCompletEnCsv)
                  && ($this->slugifyProduitArrayOrString($produit->getLibelleFormat()) != $libelleCompletEnCsv)) {
                    continue;
                  }
                }elseif((count(array_diff($csvLibelleProductArray, $produitConfLibelleAOC))) && (count(array_diff($csvLibelleProductArray, $produitConfLibelleAOP)))
                    && ($libelleCompletConfAOC != $csvLibelleProductComplet) && ($libelleCompletConfAOP != $csvLibelleProductComplet)
                    && ($libelleCompletConfAOC != $libelleCompletEnCsv) && ($libelleCompletConfAOP != $libelleCompletEnCsv)
                    && ($this->slugifyProduitArrayOrString($produit->getLibelleFormat()) != $libelleCompletEnCsv)) {
                    continue;
                }
                $founded_produit = $produit;
            }
        }
        return $founded_produit;
    }


    private function checkCommercialisationFields(){

    }

    public function createDae($csvRow,$produit){
        $identifiant = $csvRow[self::CSV_IDENTIFIANT];
        $date = $csvRow[self::CSV_DATE_COMMERCIALISATION];


        $dae = DAEClient::getInstance()->createSimpleDAE($identifiant,$date);
        $dae->produit_key = $produit->getHash();
        $dae->produit_libelle = $produit->getLibelleFormat();
        $dae->type_acheteur_key = $csvRow[self::CSV_ACHETEUR_TYPE];
        $dae->destination_key = $csvRow[self::CSV_PAYS_NOM];
        $dae->millesime = $csvRow[self::CSV_PRODUIT_MILLESIME];
        $dae->contenance_key = $csvRow[self::CSV_CONTENANCE_CONDITIONNEMENT];
        $dae->quantite = $csvRow[self::CSV_QUANTITE_CONDITIONNEMENT];
        $dae->prix_hl = $csvRow[self::CSV_PRIX_UNITAIRE];
        $dae->calculateDatas();
        return $dae;
    }

    private function convertNumber($number){
          $numberPointed = trim(str_replace(",",".",$number));
          return round(floatval($numberPointed), 4);
    }


    /**
     * Functions de création d'erreurs
     */

     private function createWrongCsvFileError($num_ligne, $csvRow) {
         return $this->createError($num_ligne,"",
                                      "Ce fichier n'est pas un fichier Csv valide.",
                                      CSVDAEClient::LEVEL_ERROR);
     }

     private function createWrongFormatFieldCountError($num_ligne, $csvRow) {
         return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_DATE_COMMERCIALISATION]), "La ligne ne possède pas le bon nombre de champs.");
     }

     private function createWrongFormatDateCommercialisationError($num_ligne, $csvRow) {
         return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_DATE_COMMERCIALISATION]), "Le format de la date de commercialisation n'est pas bon (format attendu AAAA-MM-JJ).");
     }

     private function createDifferentEtbError($num_ligne, $csvRow) {
         return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]), "L'établissement du fichier n'existe pas ou n'est pas le bon établissement.");
     }

     private function productNotFoundError($num_ligne, $csvRow) {
         $libellesArray = $this->buildLibellesArrayWithRow($csvRow);
         return $this->createError($num_ligne, implode(' ', $libellesArray), "Le produit n'a pas été trouvé");
     }



        private function createError($num_ligne, $erreur_csv, $raison, $level = CSVDAEClient::LEVEL_WARNING) {
            $error = new stdClass();
            $error->num_ligne = $num_ligne;
            $error->erreur_csv = $erreur_csv;
            $error->raison = $raison;
            $error->level = $level;
            return $error;
        }

	/**
	 * Fin des functions de création d'erreurs
	 */
	private function buildLibellesArrayWithRow($csvRow, $with_slugify = false) {
	    $certification = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_CERTIFICATION]) : $csvRow[self::CSV_PRODUIT_CERTIFICATION];
	    $genre = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_GENRE]) : $csvRow[self::CSV_PRODUIT_GENRE];
	    $appellation = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_APPELLATION]) : $csvRow[self::CSV_PRODUIT_APPELLATION];
	    $mention = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_MENTION]) : $csvRow[self::CSV_PRODUIT_MENTION];
	    $lieu = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_LIEU]) : $csvRow[self::CSV_PRODUIT_LIEU];
	    $couleur = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_COULEUR]) : $csvRow[self::CSV_PRODUIT_COULEUR];
	    $cepage = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_PRODUIT_CEPAGE]) : $csvRow[self::CSV_PRODUIT_CEPAGE];

	    $libelles = array(strtoupper($certification),
		strtoupper($genre),
		strtoupper($appellation),
		strtoupper($mention),
		strtoupper($lieu),
		strtoupper($couleur),
		strtoupper($cepage));
	    foreach ($libelles as $key => $libelle) {
		if (!$libelle) {
		    $libelles[$key] = null;
		}
	    }
	    return $libelles;
	}

    private function slugifyProduitArrayOrString($produitLibelles) {
          $produitLibellesStr = is_array($produitLibelles)? implode(" ",$produitLibelles) : $produitLibelles;
          return strtoupper(KeyInflector::slugify(trim(preg_replace("/[\ ]+/"," ",$produitLibellesStr))));
    }

    private function slugifyProduitConf($produit, $withAOP = false, $withGenre = true) {
        $libellesSlugified = array();
        foreach ($produit->getLibelles() as $key => $libelle) {
            $libellesSlugified[] = strtoupper(KeyInflector::slugify($libelle));
        }
        $genreKey = $produit->getGenre()->getKey();
        $genreLibelle = self::$genres[$genreKey];
        $libellesSlugified[1] = strtoupper(KeyInflector::slugify($genreLibelle));
        if(($libellesSlugified[0] == "AOC") && $withAOP){
            $libellesSlugified[0]="AOP";
        }
        foreach ($libellesSlugified as $key => $libelle) {
            if (!$libelle) {
                $libellesSlugified[$key] = null;
            }
        }
        return $libellesSlugified;
    }

    private function isEmptyArray($array){
      foreach ($array as $csvLibelle) {
        if($csvLibelle){
          return false;
        }
      }
        return true;
    }
}
