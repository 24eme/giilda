<?php

class DSClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("DS");
    }

    public function getId($campagne, $identifiant) {
        return 'DS-' . $campagne . '-' . $identifiant;
    }

    public function getNextNoFacture($campagne, $identifiant) {
        $id = '';
        $ds = self::getAtDate($campagne, $identifiant, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($ds) > 0) {
            $id .= ((double) str_replace('DS-' . $campagne . '-', '', max($ds)) + 1);
        } else {
            $id.= $identifiant . '-01';
        }
        return $id;
    }

    public function getAtDate($campagne, $identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('DS-' . $campagne . '-' . $identifiant . '-00')->endkey('DS-' . $identifiant . '-99')->execute($hydrate);
    }

    public function createDsByEtb($campagne, $etablissement) {
        $ds = new DS();
        $ds->date_emission = date('Y-m-d');
        $ds->campagne = $campagne;
        $ds->identifiant = $etablissement->identifiant;
        $ds->_id = $this->getId($campagne, $ds->identifiant);
        $ds->storeDeclarant();
        $ds->updateProduits();
        return $ds;
    }
    
    public function getHistoryByOperateur($etablissement) {
        return DSHistoryView::getInstance()->findByEtablissement($etablissement->identifiant);
    }

}
