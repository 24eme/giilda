<?php 

class DSHistoryView extends acCouchdbView
{
    const KEY_IDENTIFIANT = 0;
    const KEY_CAMPAGNE = 1;
    const KEY_STATUT = 2;

    const VALUE_DS_ID = 0;
    const VALUE_DECLARANT_CVI = 1;

    public static function getInstance() {

        return acCouchdbManager::getView('ds', 'history', 'DS');
    }

    public function findByEtablissement($identifiant) {        
        return $this->client->startkey(array($identifiant))
                            ->endkey(array($identifiant, array()))
                            ->getView($this->design, $this->view)->rows;
    }

    public function findByEtablissementAndPeriode($identifiant, $campagne) {
        return $this->client->startkey(array($identifiant, $campagne))
                            ->endkey(array($identifiant, $campagne, array()))
                            ->getView($this->design, $this->view)->rows;
    }
}