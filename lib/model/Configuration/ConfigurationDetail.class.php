<?php

/**
 * Model for ConfigurationDetail
 *
 */
class ConfigurationDetail extends BaseConfigurationDetail {

    public function getCVO($periode, $interpro) {
        return $this->getParent()->getParent()->getCVO($periode, $interpro);
    }

    public static function getTypeDRMByKey($key) {
        if($key == DRM::DETAILS_KEY_SUSPENDU) {

            return DRMClient::TYPE_DRM_SUSPENDU;
        }

        if($key == DRM::DETAILS_KEY_ACQUITTE) {

            return DRMClient::TYPE_DRM_ACQUITTE;
        }

        return null;
    }

    public function getTypeDRM() {

        return self::getTypeDRMByKey($this->getKey());
    }

    public static function getTypeDRMLibelleByKey($key) {
        if($key == DRM::DETAILS_KEY_SUSPENDU) {

            return "Suspendu";
        }

        if($key == DRM::DETAILS_KEY_ACQUITTE) {

            return "Acquitté";
        }

        return null;
    }

    public function getTypeDRMLibelle() {

        return self::getTypeDRMLibelleByKey($this->getKey());
    }

    public function getAllDetails() {
        $detailskeys = array('stocks_debut' => array(), 'entrees' => array(),'sorties' => array(),'stocks_fin' => array());
        foreach ($this->getDetailsSorted($this->getStocksDebut()) as $key_detail => $detail) {
            $detailskeys['stocks_debut'][$key_detail] = $detail;
        }
        foreach ($this->getDetailsSorted($this->getEntrees()) as $key_detail => $detail) {
            $detailskeys['entrees'][$key_detail] = $detail;
        }
        foreach ($this->getDetailsSorted($this->getSorties()) as $key_detail => $detail) {
            $detailskeys['sorties'][$key_detail] = $detail;
        }
        foreach ($this->getDetailsSorted($this->getStocksFin()) as $key_detail => $detail) {
            $detailskeys['stocks_fin'][$key_detail] = $detail;
        }
        return $detailskeys;
    }

    public function getEntreesSorted() {
        return $this->getDetailsSorted($this->getEntrees());
    }

    public function getSortiesSorted() {
        return $this->getDetailsSorted($this->getSorties());
    }

    public function getDetailsSorted($details) {
        $detailsSortedLibelle = array();
        foreach ($details as $key => $detail) {
            $detailsSortedLibelle[$detail->getLibelle()] = $detail;
        }
        ksort($detailsSortedLibelle);
        $detailsSorted = array();
        foreach ($detailsSortedLibelle as $keyLibelle => $detail) {
            $detailsSorted[$detail->getKey()] = $detail;
        }

        return $detailsSorted;
    }

    public function isWritableForEtablissement($cat,$type,$etb) {
        return $this->get($cat)->get($type)->isWritableForEtablissement($etb);
    }
}
