<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class EtablissementRegionView
 * @author mathurin
 */
class EtablissementRegionView extends acCouchdbView {

    const KEY_FAMILLE = 0;
    const KEY_REGION = 1;
    const KEY_COMMUNE = 2;
    const KEY_CODE_POSTAL = 3;
    const KEY_CVI = 4;
    const KEY_NOM = 5;
    const KEY_IDENTIFIANT = 6;

    public static function getInstance() {
        return acCouchdbManager::getView('etablissement', 'region', 'Etablissement');
    }

    public function findAll() {
        return $this->client->limit(100)->getView($this->design, $this->view);
    }

    public function findByFamillesAndRegions($familles, $regions, $limit = 100) {
        $etablissements = array();
        foreach ($familles as $famille) {
            foreach ($regions as $region) {                
                $etablissements = array_merge($etablissements, $this->findByFamilleAndRegion($famille, $region, $limit));
            }            
        }
        return $etablissements;
    }

    public function findByFamilleAndRegion($famille, $region = null, $limit = 100) {
        $query = null;
        if ($region) {
            $query = $this->client->startkey(array($famille, $region))
                    ->endkey(array($famille, $region, array()));
        } else {
            $query = $this->client->startkey(array($famille))
                    ->endkey(array($famille, array()));
        }
        
        if ($limit == null) {
            return $query->getView($this->design, $this->view)->rows;
        }
        return $query->limit($limit)->getView($this->design, $this->view)->rows;
    }

}
