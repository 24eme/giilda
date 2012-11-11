<?php

/**
 * Model for RevendicationErreurs
 *
 */
class RevendicationErreurs extends BaseRevendicationErreurs {

    public function storeErreur($numLigne, $row, $erreurException) {
        $rowFormatted = implode('#', $row);
        if (!$rowFormatted) {
            throw new sfException("La ligne " . $numLigne . " est mal formattée.");
        }
        switch ($erreurException->getErrorType()) {
            case RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS:
                $errorData = $this->add($row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT]);
                $error = $errorData->add();                
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT],$row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT]);
                break;
            case RevendicationErrorException::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS:
                $errorData = $this->add($row[RevendicationCsvFile::CSV_COL_CVI]);
                $error = $errorData->add();
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_CVI];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_CVI]);
                break;
            case RevendicationErrorException::ERREUR_TYPE_BAILLEUR_NOT_EXISTS:
                $errorData = $this->add($row[RevendicationCsvFile::CSV_COL_BAILLEUR]);
                $error = $errorData->add();
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_BAILLEUR];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_BAILLEUR_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_BAILLEUR]);
                break;
            case RevendicationErrorException::ERREUR_TYPE_DOUBLON:
                $ligne_doublee = $erreurException->getArguments();
                if(!isset($ligne_doublee['num_ligne'])) throw new sfException("Error DOUBLON without the doubled row");
                $errorData = $this->add($ligne_doublee['num_ligne']);
                $error = $errorData->add();
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_NUMERO_CA];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_DOUBLON_LIBELLE, $row[RevendicationCsvFile::CSV_COL_CVI], $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT], sprintf("%01.02f", round(floatval($row[RevendicationCsvFile::CSV_COL_VOLUME]), 2)));
                break;

            default:
                echo $numLigne;
                break;
        }
        $error->ligne = $rowFormatted;
        $error->num_ligne = $numLigne;
    }

}