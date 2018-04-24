<?php

/**
 * Model for RelanceTypeExplications
 *
 */
class RelanceTypeExplications extends BaseRelanceTypeExplications {

    public function storeVerificationForAlerte($alerte) {
        sfApplicationConfiguration::getActive()->loadHelpers('Float');
        sfApplicationConfiguration::getActive()->loadHelpers('Date');
        $this->origine_identifiant = $alerte->value[AlerteRechercheView::VALUE_ID_DOC];
        $this->origine_libelle = $alerte->value[AlerteRechercheView::VALUE_LIBELLE_DOCUMENT];
        $this->origine_date = date('Y-m-d');
        $this->alerte_identifiant = $alerte->id;
        $type_alerte = $alerte->key[AlerteRelanceView::KEY_TYPE_ALERTE];
        switch ($type_alerte) {
            case AlerteClient::DRM_MANQUANTE:
            case AlerteClient::DRA_MANQUANTE:
                $id = $alerte->value[AlerteRelanceView::VALUE_ID_DOC];
                if (preg_match('/^DRM-([0-9]{8})-([0-9]{6})*/', $id, $matches)) {
                    $periode = $matches[2];
                }
                if (!$periode)
                    throw new sfException("La periode de l'alerte $alerte->id ne peut être identifiée.");
                $doc = DRMClient::getInstance()->find($alerte->value[AlerteRelanceView::VALUE_ID_DOC]);
                $this->explications = ConfigurationClient::getInstance()->getPeriodeLibelle($periode);
                break;
            default:
                throw new sfException("L'alerte $alerte->id de type $type_alerte n'a pas été trouvée.");
                break;
        }
    }

}
