<?php

class DRMMouvementsFactureView extends acCouchdbView
{
    const KEY_FACTURE = 0;
    const KEY_FACTURABLE = 1;
    const KEY_ETABLISSEMENT_IDENTIFIANT = 2;
    const KEY_PRODUIT_HASH = 3;
    const KEY_TYPE_HASH = 4;

    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_VOLUME = 2;
    const VALUE_DETAIL_LIBELLE = 3;
    const VALUE_CVO = 4;
    const VALUE_NUMERO = 5;

    public static function getInstance() {

        return acCouchdbManager::getView('drm', 'mouvements_facture', 'DRM');
    }
    
    
    public function getFacturationByEtablissement($etablissement,$facturee, $facturable) {
        
        return $this->client
            ->startkey(array($facturee,$facturable,$etablissement->identifiant))
            ->endkey(array($facturee,$facturable,$etablissement->identifiant, array()))
            ->getView($this->design, $this->view)->rows;
    }

    public function getAFactureByEtablissement($etablissement) {

        return $this->buildMouvements($this->getFacturationByEtablissement($etablissement, 0, 1));      
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