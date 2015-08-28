<?php

/**
 * Model for ConfigurationDetail
 *
 */
class ConfigurationDetail extends BaseConfigurationDetail {

    public function getCVO($periode, $interpro) {
        return $this->getParent()->getParent()->getCVO($periode, $interpro);
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

}
