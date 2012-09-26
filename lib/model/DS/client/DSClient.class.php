<?php

class DSClient extends acCouchdbClient {

    const STATUT_VALIDE = 'valide';
    const STATUT_VALIDE_PARTIEL = 'valide_partiel'; 
    const STATUT_BROUILLON = 'brouillon'; 
    
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
        $ds->statut = self::STATUT_BROUILLON;
        $ds->_id = $this->getId($campagne, $ds->identifiant);
        $ds->storeDeclarant();
        $ds->updateProduits();
        return $ds;
    }

    public function getHistoryByOperateur($etablissement) {
        return DSHistoryView::getInstance()->findByEtablissement($etablissement->identifiant);
    }

    public function findByCampagneAndIdentifiant($campagne, $identifiant) {
        return $this->find($this->getId($campagne, $identifiant));
       
    }
    
    public function getLinkLibelleForHistory($statut)
    {
        if($statut == self::STATUT_BROUILLON) return '> Démarrer la saisie';
        if($statut == self::STATUT_VALIDE_PARTIEL) return '> Consulter/Modifier';
        if($statut == self::STATUT_VALIDE) return '> Consulter';
        return '';
    }

}
