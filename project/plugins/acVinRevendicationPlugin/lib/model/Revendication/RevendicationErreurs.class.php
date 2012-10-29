<?php

/**
 * Model for RevendicationErreurs
 *
 */
class RevendicationErreurs extends BaseRevendicationErreurs {

    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS = "ETABLISSEMENT";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS = "PRODUIT";
    const ERREUR_TYPE_BAILLEUR_NOT_EXISTS = "BAILLEUR";
    const ERREUR_TYPE_DOUBLON = "DOUBLON";
    
    const ERREUR_TYPE_DOUBLON_LIBELLE = "L'etablissement de cvi %s a déjà possède déjà un volume revendiqué pour le produit %s (%s hl).";
    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE = "L'etablissement de cvi %s n'existe pas.";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE = "Le produit %s n'existe pas.";
    const ERREUR_TYPE_BAILLEUR_NOT_EXISTS_LIBELLE = "Le bailleur %s n'existe pas.";

    public function storeErreur($numLigne, $row, $erreur_type) {
        
        $rowFormatted = implode('#', $row);
        $this->num_ligne = $numLigne;
        $this->type_erreur = $erreur_type;
        if(!$rowFormatted) {
            throw new sfExcpetion("La ligne ".$numLigne." est mal formattée.");
        }
        switch ($erreur_type) {
            case self::ERREUR_TYPE_PRODUIT_NOT_EXISTS:
                $this->libelle_erreur = sprintf(self::ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT]);
                $this->data_erreur = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
                $this->ligne = $rowFormatted;
               break;
            case self::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS:
                $this->libelle_erreur = sprintf(self::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_CVI]);
                $this->data_erreur = $row[RevendicationCsvFile::CSV_COL_CVI];
                $this->ligne = $rowFormatted;
                break;
            case self::ERREUR_TYPE_BAILLEUR_NOT_EXISTS:
                $this->libelle_erreur = sprintf(self::ERREUR_TYPE_BAILLEUR_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_BAILLEUR]);
                $this->data_erreur = $row[RevendicationCsvFile::CSV_COL_BAILLEUR];
                $this->ligne = $rowFormatted;
                break;
            
            case self::ERREUR_TYPE_DOUBLON:
                $this->libelle_erreur = sprintf(self::ERREUR_TYPE_DOUBLON_LIBELLE,
                                                $row[RevendicationCsvFile::CSV_COL_CVI],
                                                $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT],
                                                sprintf("%01.02f", round(floatval($row[RevendicationCsvFile::CSV_COL_VOLUME]), 2)));
                $this->data_erreur = $row[RevendicationCsvFile::CSV_COL_NUMERO_CA];
                $this->ligne = $rowFormatted;
                break;
            
            default:
                echo $numLigne;
                break;
        }
    }

}