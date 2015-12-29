<?php 

class VracCsvFile extends CsvFile 
{
    const CSV_NUMERO_PAPIER = 0;
    const CSV_NUMERO_CONTRAT = 1;
    const CSV_DATE_SIGNATURE = 2;
    const CSV_DATE_SAISIE = 3;

    const CSV_TYPE_TRANSACTION = 4;

    const CSV_VENDEUR_ID = 5;
    // const CSV_VENDEUR_NOM = 6;
    // const CSV_VENDEUR_CVI = 7;
    // const CSV_VENDEUR_ACCISES = 4;
    // const CSV_VENDEUR_ADRESSE = 4;
    // const CSV_VENDEUR_COMMUNE = 4;
    // const CSV_VENDEUR_CODE_POSTAL = 4;
    const CSV_VENDEUR_VIN_LOGEMENT_AUTRE = 6;

    const CSV_INTERMEDIAIRE_ID = 7;

    const CSV_ACHETEUR_ID = 8;
    // const CSV_ACHETEUR_NOM = 4;
    // const CSV_ACHETEUR_CVI = 4;
    // const CSV_ACHETEUR_ACCISES = 4;
    // const CSV_ACHETEUR_ADRESSE = 4;
    // const CSV_ACHETEUR_COMMUNE = 4;
    // const CSV_ACHETEUR_CODE_POSTAL = 4;

    const CSV_COURTIER_ID = 9;
    // const CSV_COURTIER_NOM = 4;
    // const CSV_COURTIER_CARTE_PRO = 4;
    // const CSV_COURTIER_ADRESSE = 4;
    // const CSV_COURTIER_COMMUNE = 4;
    // const CSV_COURTIER_CODE_POSTAL = 4;   

    const CSV_PRODUIT_ID = 10;
    const CSV_PRODUIT_LIBELLE = 11;
    const CSV_MILLESIME = 12;
    const CSV_CEPAGE_ID = 13;
    const CSV_CEPAGE_LIBELLE = 14;

    const CSV_CATEGORIE_VIN = 15;
    const CSV_CATEGORIE_VIN_INFO = 16;
    
    const CSV_SURFACE = 17;
    const CSV_LOT = 18;
    const CSV_DEGRE = 19;

    const CSV_QUANTITE = 20;
    const CSV_QUANTITE_UNITE = 21;

    const CSV_VOLUME_PROPOSE = 22;
    const CSV_VOLUME_ENLEVE = 23;

    const CSV_PRIX_UNITAIRE = 24;
    const CSV_PRIX_UNITAIRE_HL = 25;

    const CSV_DELAI_PAIEMENT = 26;
    const CSV_ACOMPTE_SIGNATURE = 27;
    const CSV_MOYEN_PAIEMENT = 28;
    const CSV_TAUX_COURTAGE = 29;
    const CSV_REPARTITION_COURTAGE = 30;

    const CSV_REPARTITION_CVO = 31;

    const CSV_RETIRAISON_DATE_DEBUT = 32;
    const CSV_RETIRAISON_DATE_FIN = 33;

    const CSV_CLAUSES = 34;
    const CSV_COMMENTAIRES = 35;

    public function import() {
        $this->errors = array();
        $csvs = $this->getCsv();

        $configuration = ConfigurationClient::getInstance()->getCurrent();

        foreach ($csvs as $line) {
            try {
                $v = new Vrac();

                $v->date_signature = $this->verifyAndFormatDateSignature($line);
                $v->date_campagne = $v->date_signature;
                $v->valide->date_saisie = $this->verifyAndFormatDateSaisie($line);

                $dateSaisie = new DateTime($v->valide->date_saisie);
                $v->numero_contrat = $this->verifyAndFormatNumeroContrat($line);
                $v->numero_archive = $this->verifyAndFormatNumeroArchive($line);
                $v->_id = $this->verifyAndFormatIdContrat($line);
              //  $v->constructId();
                
                if(VracClient::getInstance()->find($v->_id, acCouchdbClient::HYDRATE_JSON)) {
                    throw new sfException(sprintf($this->red("Existe")));
                }

                $vendeur = $this->verifyEtablissement($line[self::CSV_VENDEUR_ID]);
                $acheteur = $this->verifyEtablissement($line[self::CSV_ACHETEUR_ID]);
                
                $representant = null;
                if($line[self::CSV_INTERMEDIAIRE_ID]) {
                    $representant = $this->verifyEtablissement($line[self::CSV_INTERMEDIAIRE_ID]);
                }

                $courtier = null;
                if($line[self::CSV_COURTIER_ID]) {
                    $courtier = $this->verifyEtablissement($line[self::CSV_COURTIER_ID]);
                }

                $v->vendeur_identifiant = $vendeur->_id;
                
                if($representant) {
                    $v->representant_identifiant = $representant->_id;
                } else {
                    $v->representant_identifiant = $vendeur->_id;
                }

                $v->acheteur_identifiant = $acheteur->_id;
                $v->mandataire_exist = false;

                if($courtier) {
                    $v->mandataire_exist = true;
                    $v->mandataire_identifiant = $courtier->_id;
                }
                $v->setInformations();

                $produit = $configuration->identifyProductByLibelle($line[self::CSV_PRODUIT_LIBELLE]);

                if($produit) {
                    $v->setProduit($produit->getHash());
                }

                if(!$v->produit) {
                    throw new sfException(sprintf("Le produit n'a pas été trouvé %s", $this->yellow($line[self::CSV_PRODUIT_LIBELLE])));
                }

                $this->verifyTypeTransaction($line);
                $v->type_transaction = $line[self::CSV_TYPE_TRANSACTION];
                $v->millesime = $this->verifyAndFormatMillesime($line);
                $v->categorie_vin = $this->verifyAndFormatCatgeorieVin($line);
                $v->degre = $this->verifyAndFormatDegre($line);
                $v->jus_quantite = $this->verifyAndFormatVolumePropose($line);
                $v->volume_enleve = $v->volume_propose;
                $v->prix_initial_unitaire = $this->formatAndVerifyPrixUnitaire($line);
                $v->prix_initial_unitaire_hl = $v->prix_initial_unitaire;
                $v->date_debut_retiraison = $this->formatAndVerifyDateRetiraisonDebut($line);
                $v->date_limite_retiraison = $this->formatAndVerifyDateRetiraisonFin($line);

                if($v->date_debut_retiraison > $v->date_limite_retiraison) {
                    throw new sfException($this->red("La date de début de retiraison est supérieur à celle du début"));
                }
                
                $v->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
                $v->update();
                $v->enleverVolume($v->volume_propose);

                $v->save();
                echo sprintf("Le contrat %s a bien été importé\n", $this->green($v->_id));
            }catch(Exception $e) {
                echo sprintf("%s : #%s\n",$this->red($e->getMessage()), implode(";", $line));
                $this->error[] = $e->getMessage();
            }
        }
    } 

    private function verifyAndFormatNumeroContrat($line) {
        if($line[self::CSV_NUMERO_PAPIER] && preg_match('/[0-9]+/', $line[self::CSV_NUMERO_PAPIER]) && strlen(trim($line[self::CSV_NUMERO_PAPIER])) <= 11) {
          
            return sprintf("%07d", $line[self::CSV_NUMERO_PAPIER]);
        }

        /*if($line[self::CSV_NUMERO_CONTRAT] && preg_match('/[0-9]+/', $line[self::CSV_NUMERO_CONTRAT])) {

            return sprintf("%06d", $line[self::CSV_NUMERO_CONTRAT]);
        }*/

        throw new Exception(sprintf("Le numéro de contrat en nul ou au mauvais format %s", $line[self::CSV_NUMERO_PAPIER]));
    }

    private function verifyAndFormatNumeroArchive($line) {
        if($line[self::CSV_NUMERO_CONTRAT] && preg_match('/[0-9]+/', $line[self::CSV_NUMERO_CONTRAT])) {

            return sprintf("%05d", $line[self::CSV_NUMERO_CONTRAT]);
        }

        throw new Exception(sprintf("Le numéro d'archive en nul ou au mauvais format %s", $line[self::CSV_NUMERO_CONTRAT]));
    }
    
    private function verifyAndFormatIdContrat($line) {
        if($line[self::CSV_ID_CONTRAT] && preg_match('/[0-9]{11}/', $line[self::CSV_ID_CONTRAT])) {

            return sprintf("%11d", $line[self::CSV_ID_CONTRAT]);
        }

        throw new Exception(sprintf("L'id du contrat est nul ou au mauvais format %s", $line[self::CSV_ID_CONTRAT]));
    }

    private function verifyAndFormatDateSignature($line) {
        $date = $line[self::CSV_DATE_SIGNATURE];

        if(!$date) {
            throw new Exception(sprintf("La date de signature est requise", $date));
        }
        
        return $this->formatAndVerifyDate($date);
    }

    private function verifyAndFormatDateSaisie($line) {
        $date = $line[self::CSV_DATE_SAISIE];

        if(!$date) {
            throw new Exception(sprintf("La date de saisie est requise", $date));
        }

        return $this->formatAndVerifyDate($date);
    }

    private function verifyEtablissement($id) {
        if(strlen($id) < 6) {

            $id = sprintf("%06d01", $id);
        }

        $etablissement = EtablissementClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);

        if(!$etablissement) {
            throw new Exception(sprintf("L'établissement %s n'existe pas", $id));
        }

        return $etablissement;
    }

    private function verifyTypeTransaction($line) {

    }

    private function verifyAndFormatMillesime($line) {

        return $line[self::CSV_MILLESIME] ? (int) $line[self::CSV_MILLESIME] : null;
    }

    private function verifyAndFormatCatgeorieVin($line) {
        
        return ($line[self::CSV_CATEGORIE_VIN]) ? $line[self::CSV_CATEGORIE_VIN] : VracClient::CATEGORIE_VIN_GENERIQUE;
    }

    private function verifyAndFormatDegre($line) {
        
        if(!trim($line[self::CSV_DEGRE])) {
            return null;
        }

        return $this->formatAndVerifyFloat($line[self::CSV_DEGRE]);
    }

    private function verifyAndFormatVolumePropose($line) {
        
        $number = $this->formatAndVerifyFloat($line[self::CSV_VOLUME_PROPOSE]);

        if(!$number) {

            throw new Exception(sprintf("Le volume proposé est requis", $number));
        }

        return $number;
    }

    private function formatAndVerifyPrixUnitaire($line) {
        $number = $this->formatAndVerifyFloat($line[self::CSV_PRIX_UNITAIRE_HL]);

        if(!$number) {

            throw new Exception(sprintf("Le prix unitaire est requis", $number));
        }

        return $number;
    }

    private function formatAndVerifyDateRetiraisonDebut($line) {
        $date = $line[self::CSV_RETIRAISON_DATE_DEBUT];

        if(!$date) {
            return null;
            throw new Exception(sprintf("La date de début de retiraison est requise", $date));
        }

        return $this->formatAndVerifyDate($date);
    }

    private function formatAndVerifyDateRetiraisonFin($line) {
        $date = $line[self::CSV_RETIRAISON_DATE_DEBUT];

        if(!$date) {
            return null;
            throw new Exception(sprintf("La date de fin de retiraison est requise", $date));
        }

        return $this->formatAndVerifyDate($date);
    }

    private function formatAndVerifyFloat($number) {
        $number = (float) str_replace(",", ".", $number);

        if(!is_float($number)) {

            throw new Exception(sprintf("Number %s is not a float", $number));
        }

        return (float) FloatHelper::getInstance()->format($number);
    }

    private function formatAndVerifyDate($date) {
        $date = preg_replace("|^([0-9]+)/([0-9]+)/([0-9]+)$|", '\3-\2-\1', $date);
        $date = preg_replace("|^([0-9]{4})([0-9]{2})([0-9]{2})$|", '\1-\2-\3', $date);
        $date = preg_replace("|^([0-9]{2})([0-9]{2})([0-9]{4})$|", '\3-\2-\1', $date);

        $date = new DateTime($date);

        return $date->format('Y-m-d');
    }


    public function getErrors() {
        return $this->errors;
    }
  
     public function green($string) {
        return "\033[32m" . $string . "\033[0m";
    }

    public function yellow($string) {
        return "\033[33m" . $string . "\033[0m";
    }

    public function red($string) {
        return "\033[31m" . $string . "\033[0m";
    }

}
