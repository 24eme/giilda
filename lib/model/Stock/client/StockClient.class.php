<?php

class StockClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Stock");
    }

    public function getId($campagne, $identifiant) {
        return 'STOCK-' . $campagne . '-' . $identifiant;
    }

    public function getNextNoFacture($campagne, $identifiant) {
        $id = '';
        $stock = self::getAtDate($campagne, $identifiant, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($stock) > 0) {
            $id .= ((double) str_replace('STOCK-' . $campagne . '-', '', max($stock)) + 1);
        } else {
            $id.= $identifiant . '-01';
        }
        return $id;
    }

    public function getAtDate($campagne, $identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('STOCK-' . $campagne . '-' . $identifiant . '-00')->endkey('STOCK-' . $identifiant . '-99')->execute($hydrate);
    }

    public function createStockByEtb($campagne, $etablissement) {
        $stock = new Stock();
        $stock->date_emission = date('Y-m-d');
        $stock->campagne = $campagne;
        $stock->identifiant = $etablissement->identifiant;
        $stock->_id = $this->getId($campagne, $stock->identifiant);
        $stock->storeDeclarant();
        $stock->updateProduits();
        return $stock;
    }
    
    public function getHistoryByOperateur($etablissement) {
        return StockHistoryView::getInstance()->findByEtablissement($etablissement->identifiant);
    }

}
