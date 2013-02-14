<?php

/**
 * Model for RevendicationErreurs
 *
 */
class RevendicationErreurs extends BaseRevendicationErreurs {

    public function storeErreur($numLigne, $row, $erreurException) {
        switch ($erreurException->getErrorType()) {
            case RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS:
                $errorData = $this->add($row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT]);
                $error = $errorData->add($numLigne);
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT],$row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT]);
                break;
            case RevendicationErrorException::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS:
                $errorData = $this->add($row[RevendicationCsvFile::CSV_COL_CVI]);
                $error = $errorData->add($numLigne);
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_CVI];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE, $row[RevendicationCsvFile::CSV_COL_CVI]);
                break;
            case RevendicationErrorException::ERREUR_TYPE_BAILLEUR_NOT_EXISTS:
                $args = $erreurException->getArguments();
                $errorData = $this->add($args['identifiant'].'_'.$row[RevendicationCsvFile::CSV_COL_BAILLEUR]);
                $error = $errorData->add($numLigne);
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_BAILLEUR];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_BAILLEUR_NOT_EXISTS_LIBELLE, $args['identifiant'], $row[RevendicationCsvFile::CSV_COL_BAILLEUR]);
                break;
            case RevendicationErrorException::ERREUR_TYPE_DOUBLON:
                $info_doublon = $erreurException->getArguments();
                $errorData = $this->add($info_doublon['md5']);
		if (isset($info_doublon['inserted_volume'])) {
		  $errorO = $errorData->add($info_doublon['inserted_volume']['numero_certification']);
		  $errorO->data_erreur = $info_doublon['inserted_volume']['numero_certification'];
		  $errorO->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_DOUBLON_LIBELLE, $row[RevendicationCsvFile::CSV_COL_CVI], $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT], sprintf("%01.02f", round(floatval($info_doublon['inserted_volume']['volume']), 2)));
		  $errorO->num_ligne = $info_doublon['inserted_volume']['num_ligne'];
		  $errorO->ligne = $info_doublon['inserted_volume']['ligne'];
		  $errorO->numero_certification = $info_doublon['inserted_volume']['numero_certification'];

		}
                $error = $errorData->add($info_doublon['num_ca']);
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_NUMERO_CA];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_DOUBLON_LIBELLE, $row[RevendicationCsvFile::CSV_COL_CVI], $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT], sprintf("%01.02f", round(floatval($row[RevendicationCsvFile::CSV_COL_VOLUME]), 2)));
                break;
            case RevendicationErrorException::ERREUR_TYPE_NO_BAILLEURS:
                $args = $erreurException->getArguments();
                $errorData = $this->add($args['identifiant']);
                $error = $errorData->add($numLigne);
                $error->data_erreur = $row[RevendicationCsvFile::CSV_COL_BAILLEUR];
                $error->libelle_erreur = sprintf(RevendicationErrorException::ERREUR_TYPE_NO_BAILLEURS_LIBELLE, $args['identifiant'],$row[RevendicationCsvFile::CSV_COL_BAILLEUR]);
                break;
                
            default:
                echo $numLigne;
                break;
        }
        $error->ligne = implode('#', $row);
        $error->num_ligne = $numLigne;
	$error->numero_certification = $row[RevendicationCsvFile::CSV_COL_NUMERO_CA];
        return $error;
    }

}