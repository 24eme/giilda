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
        return array_reverse(RevendicationHistoryView::getInstance()->getHistory());
    }

    public function getRevendicationLibelle($id) {
        $params = $this->getParametersFromId($id);
        return 'Revendication de ' . $params['campagne'] . ' (' . $params['odg'] . ')';
    }

    public function getParametersFromId($id) {
        preg_match('/^REVENDICATION-([A-Z]*)-([0-9]{8})$/', $id, $matches);
        return array('odg' => strtolower($matches[1]), 'campagne' => $matches[2]);
    }

    public function getODGs() {
        return EtablissementClient::getRegionsWithoutHorsInterLoire();
    }

    public function deleteRow($revendication, $identifiant, $produit, $row) {
        if (!isset($revendication->datas->$identifiant))
            throw new sfException("Le noeud d'identifiant $identifiant n'existe pas dans la revendication");
        $produitNode = $this->getProduitNode($revendication, $identifiant, $produit);
        if (!$produitNode)
            throw new sfException("Le noeud produit d'identifiant $identifiant et de produit $produit n'existe pas dans la revendication");
        if (!$produitNode->volumes->$row)
            throw new sfException("La ligne $row n'existe pas pour le produit $produit et l'etablissement $identifiant");
        $produitNode->volumes->$row->statut = RevendicationProduits::STATUT_SUPPRIME;
        $this->storeDoc($revendication);
    }

    public function getProduitNode($revendication, $identifiant, $produit) {
        if(!isset($revendication->datas->$identifiant->produits->$produit))
            return null;
        return $revendication->datas->$identifiant->produits->$produit;
    }

    
    public function deleteRevendication($revendication){   
        $this->delete($revendication);
    }
}
