<?php

class MouvementFacturationView extends acCouchdbView
{
    
    const KEYS_FACTURE = 0;
    const KEYS_FACTURABLE = 1;
    const KEYS_REGION = 2;
    const KEYS_ETB_ID = 3;
    const KEYS_ORIGIN = 4;
    const KEYS_MATIERE = 5;
    const KEYS_PRODUIT_ID = 6;
    const KEYS_PERIODE = 7;
    const KEYS_CONTRAT_ID = 8;
    const KEYS_MVT_TYPE = 9;
    const KEYS_DETAIL_ID = 10;
                    
    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_VOLUME = 2;
    const VALUE_CVO = 3;
    const VALUE_DATE = 4;
    const VALUE_DETAIL_LIBELLE = 5;
    const VALUE_NUMERO = 6;
    const VALUE_ORIGINE_CLES = 7;

    public static function getInstance() {

        return acCouchdbManager::getView('mouvement', 'facturation');
    }
    
    public function getMouvementsByEtablissement($etablissement,$facturee, $facturable) {        
        return $this->client
            ->startkey(array($facturee,$facturable,'tours',$etablissement->identifiant))
            ->endkey(array($facturee,$facturable,'tours',$etablissement->identifiant, array()))
            ->reduce(false)
            ->getView($this->design, $this->view)->rows;
    }
    
    public function getMouvementsByEtablissementWithReduce($etablissement,$facturee, $facturable,$level)
    {
        return $this->client
            ->startkey(array($facturee,$facturable,'tours',$etablissement->identifiant))
            ->endkey(array($facturee,$facturable,'tours',$etablissement->identifiant, array()))
            ->reduce(true)->group_level($level)
            ->getView($this->design, $this->view)->rows;
    }


    public function getMouvementsNonFacturesByEtablissement($etablissement) {

        return $this->buildMouvements($this->getMouvementsByEtablissement($etablissement, 0, 1));     
    }
    
    public function getMouvements($facturee, $facturable,$level) {
        return $this->client
            ->startkey(array($facturee,$facturable))
            ->endkey(array($facturee,$facturable, array()))
            ->reduce(true)->group_level($level)
            ->getView($this->design, $this->view)->rows;
    }

    public function getMouvementsFacturablesByRegions($facturee, $facturable,$region,$level) {
        return $this->client
            ->startkey(array($facturee,$facturable,$region))
            ->endkey(array($facturee,$facturable,$region, array()))
            ->reduce(true)->group_level($level)
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