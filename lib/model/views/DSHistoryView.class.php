<?php 

class DSHistoryView extends acCouchdbView
{
    const KEY_IDENTIFIANT = 0;
    const KEY_CAMPAGNE = 1;
    const KEY_PERIODE = 2;
    const KEY_STATUT = 3;

    const VALUE_DS_ID = 0;
    const VALUE_DECLARANT_CVI = 1;

    public static function getInstance() {

        return acCouchdbManager::getView('ds', 'history', 'DS');
    }

    public function findByEtablissement($id_or_identifiant) {    
        $identifiant = EtablissementClient::getInstance()->getIdentifiant($id_or_identifiant);    
        return $this->client->startkey(array($identifiant))
                            ->endkey(array($identifiant, array()))
                            ->getView($this->design, $this->view)->rows;
    }

    public function findByEtablissementAndPeriode($id_or_identifiant, $periode) 
    {
        $identifiant = EtablissementClient::getInstance()->getIdentifiant($id_or_identifiant);
        $campagne = $this->client->buildCampagne($periode);
        return $this->client->startkey(array($identifiant, $campagne, $periode))
                            ->endkey(array($identifiant, $campagne, $periode, array()))
                            ->getView($this->design, $this->view)->rows;
    }
}