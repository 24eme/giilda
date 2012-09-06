<?php

class FactureMouvementsDRMView extends acCouchdbView
{
    
    const KEYS_FACTURE = 0;
    const KEYS_FACTURABLE = 1;
    const KEYS_REGION = 2;
    const KEYS_ETB_ID = 3;
    const KEYS_ORIGIN = 4;
    const KEYS_MATIERE = 5;
    const KEYS_PRODUIT_ID = 6;
    const KEYS_PERIODE = 7;
    const KEYS_MVT_TYPE = 8;
    const KEYS_DETAIL_ID = 9;
    
    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_VOLUME = 2;
    const VALUE_CVO = 3;
    const VALUE_DATE = 4;
    const VALUE_DETAIL_LIBELLE = 5;
    const VALUE_NUMERO = 6;
    const VALUE_MD5_CLE = 7;
    

    public static function getInstance() {

        return acCouchdbManager::getView('facture', 'mouvements_drm', 'DRM');
    }
    
    
    public function getFacturationByEtablissement($etablissement,$facturee, $facturable) {        
        return $this->client
            ->startkey(array($facturee,$facturable,'tours',$etablissement->identifiant))
            ->endkey(array($facturee,$facturable,'tours',$etablissement->identifiant, array()))
            ->getView($this->design, $this->view)->rows;
    }
    
    public function getAFactureByEtablissement($etablissement) {

        return $this->buildMouvements($this->getFacturationByEtablissement($etablissement, 0, 1));      
    }
    
    public function getMouvementsFacturables($facturee, $facturable) {
        return $this->client
            ->startkey(array($facturee,$facturable))
            ->endkey(array($facturee,$facturable, array()))
            ->getView($this->design, $this->view)->rows;
    }

    public function getMouvementsFacturablesByRegions($facturee, $facturable,$region) {
        return $this->client
            ->startkey(array($facturee,$facturable,$region))
            ->endkey(array($facturee,$facturable,$region, array()))
            ->getView($this->design, $this->view)->rows;
    }

    protected function buildMouvements($rows) {
        $mouvements = array();
        foreach($rows as $row) {
            $mouvements[] = $this->buildMouvement($row);
        }

        return $mouvements;
    }

    protected function buildMouvement($row) {
        $mouvement = new stdClass();
        $mouvement->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
        $mouvement->type_libelle = $row->value[self::VALUE_TYPE_LIBELLE];
        $mouvement->volume = $row->value[self::VALUE_VOLUME];
        $mouvement->detail_libelle = $row->value[self::VALUE_DETAIL_LIBELLE];
        $mouvement->cvo = $row->value[self::VALUE_CVO];        
        $mouvement->numero = $row->value[self::VALUE_NUMERO];      
        return $mouvement;
    }
}  