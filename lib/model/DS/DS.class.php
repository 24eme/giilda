<?php

/**
 * Model for Facture
 *
 */
class DS extends BaseDS implements InterfaceDeclarant {

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
            $produitDs = $this->declaration->add($produit->getHashForKey());
            $produitDs->updateProduit($produit);
        }
    }

    public function getEtablissementObject() {
        return $this->declarant->getEtablissementObject();
    }

    public function storeDeclarant() {
        $this->declarant->storeDeclarant();
    }

}