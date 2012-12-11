<?php

class RevendicationClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Revendication");
    }

    public function getId($odg, $campagne) {
        return 'REVENDICATION-' . strtoupper($odg) . '-' . $campagne;
    }

    public function findByOdgAndCampagne($odg, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->find($this->getId($odg, $campagne), $hydrate);
    }

    public function getVolumeProduitObj($revendication, $cvi, $row) {
        $result = new stdClass();
        $result->produit = $revendication->getProduitNode($cvi, $row);
        $result->volume = $produit->volumes->get($row);
        return $result;
    }

    public function createOrFind($odg, $campagne) {
        $revendication = $this->find($this->getId($odg, $campagne));

        if (!$revendication) {
            $revendication = new Revendication();
            $revendication->campagne = $campagne;
            $revendication->odg = $odg;
            $revendication->_id = $this->getId($odg, $campagne);
            $revendication->date_creation = date('Y-m-d');
            $revendication->etape = 1;
            $revendication->save();
        }

        return $revendication;
    }

    public function getHistory() {
        return RevendicationHistoryView::getInstance()->getHistory();
    }

    public function getRevendicationLibelle($id) {
        $params = $this->getParametersFromId($id);
        return 'Revendication de '.$params['campagne'].' ('.$params['odg'].')';
    }

    public function getParametersFromId($id) {
        preg_match('/^REVENDICATION-([A-Z]*)-([0-9]{8})$/', $id, $matches);
        return array('odg' => strtolower($matches[1]), 'campagne' => $matches[2]);
    }

    public function getODGs() {
        return array("tours" => "Tours");
    }

}
