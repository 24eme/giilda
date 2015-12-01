<?php 

class VracCsvFile extends CsvFile 
{
    const CSV_NUMERO_CONTRAT = 0;
    const CSV_NUMERO_PAPIER = 1;

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

    const CSV_ACHETEUR_ID = 7;
    // const CSV_ACHETEUR_NOM = 4;
    // const CSV_ACHETEUR_CVI = 4;
    // const CSV_ACHETEUR_ACCISES = 4;
    // const CSV_ACHETEUR_ADRESSE = 4;
    // const CSV_ACHETEUR_COMMUNE = 4;
    // const CSV_ACHETEUR_CODE_POSTAL = 4;

    const CSV_COURTIER_ID = 8;
    // const CSV_COURTIER_NOM = 4;
    // const CSV_COURTIER_CARTE_PRO = 4;
    // const CSV_COURTIER_ADRESSE = 4;
    // const CSV_COURTIER_COMMUNE = 4;
    // const CSV_COURTIER_CODE_POSTAL = 4;   

    const CSV_PRODUIT_ID = 9;
    const CSV_PRODUIT_LIBELLE = 10;
    const CSV_MILLESIME = 11;
    const CSV_CEPAGE_ID = 12;
    const CSV_CEPAGE_LIBELLE = 13;

    const CSV_TYPE_VINS = 14;
    const CSV_TYPE_VINS_INFO = 15;
    
    const CSV_SURFACE = 16;
    const CSV_LOT = 17;
    const CSV_DEGRE = 18;

    const CSV_QUANTITE = 19;
    const CSV_QUANTITE_UNITE = 20;

    const CSV_VOLUME_PROPOSE = 21;
    const CSV_VOLUME_ENLEVE = 22;

    const CSV_PRIX_UNITAIRE = 23;
    const CSV_PRIX_UNITAIRE_HL = 24;

    const CSV_DELAI_PAIEMENT = 25;
    const CSV_ACOMPTE_SIGNATURE = 26;
    const CSV_MOYEN_PAIEMENT = 27;
    const CSV_TAUX_COURTAGE = 28;
    const CSV_REPARTITION_COURTAGE = 29;

    const CSV_REPARTITION_CVO = 30;

    const CSV_RETIRAISON_DATE_DEBUT = 31;
    const CSV_RETIRAISON_DATE_FIN = 32;

    const CSV_CLAUSES = 33;
    const CSV_COMMENTAIRES = 34;

    private function verifyCsvLine($line) {
        if (!preg_match('/[0-9]+/', $line[self::CSV_NUMERO_CONTRAT])) {

            throw new Exception(sprintf('Numéro de contrat invalide : %s', $line[self::CSV_NUMERO_CONTRAT]));
        }
    }

    public function import() {
        $this->errors = array();
        $csvs = $this->getCsv();
        foreach ($csvs as $line) {
            try {
                $this->verifyCsvLine($line);

                print_r($line);

            }catch(Exception $e) {
                echo $e->getMessage()."\n";
                $this->error[] = $e->getMessage();
            }
        }
    } 

    public function getErrors() {
        return $this->errors;
    }
  


}
