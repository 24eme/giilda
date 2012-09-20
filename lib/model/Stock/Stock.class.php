<?php

/**
 * Model for Facture
 *
 */
class Stock extends BaseStock implements InterfaceDeclarant {

    protected $declarant = null;

    public function  __construct() {
        parent::__construct();   
        $this->declarant = new Declarant($this);
    }
    
    public function getLastDRM() {
        return DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->identifiant, $this->campagne);
    }

    public function updateProduits() {
        $drm = $this->getLastDRM();
        $produits = $drm->getDetails();

        foreach ($produits as $produit) {
            var_dump($produit->getHashForKey());
            $produitStock = $this->declaration->add($produit->getHashForKey());
            $produitStock->updateProduit($produit);
        }
    }

    public function getEtablissementObject() {
        return $this->declarant->getEtablissementObject();
    }

    public function storeDeclarant() {
        $this->declarant->storeDeclarant();
    }

}