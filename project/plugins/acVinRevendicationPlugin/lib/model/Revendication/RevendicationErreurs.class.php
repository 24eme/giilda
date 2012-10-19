<?php

/**
 * Model for RevendicationErreurs
 *
 */
class RevendicationErreurs extends BaseRevendicationErreurs {

    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS = "ETABLISSEMENT";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS = "PRODUIT";
    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE = "L'etablissement de cvi %s n'existe pas.";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE = "Le produit %s n'existe pas.";

    public function storeErreur($numLigne, $row, $erreur_type) {
        $rowFormatted = implode('#', $row);
        $this->num_ligne = $numLigne;
        $this->type_erreur = $erreur_type;
        if(!$rowFormatted) {
            throw new sfExcpetion("mokrane".$numLigne."grossebite");
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
            default:
                echo $numLigne;
                break;
        }
    }

}