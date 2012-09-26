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
            $produitDs = $this->declarations->add($produit->getHashForKey());
            $produitDs->updateProduit($produit);
        }
    }

    public function getEtablissementObject() {
        return $this->declarant->getEtablissementObject();
    }

    public function storeDeclarant() {
        $this->declarant->storeDeclarant();
    }
    
    public function isStatutValide() {
        return $this->statut === DSClient::STATUT_VALIDE;
    }
    
    public function isStatutPartiel() {
        return $this->statut === DSClient::STATUT_VALIDE_PARTIEL;
    }
    
    public function isStatutASaisir() {
        return $this->statut === DSClient::STATUT_A_SAISIR;
    }
    
    public function updateStatut() {
        $this->statut = DSClient::STATUT_VALIDE;
        foreach ($this->declarations as $declaration) {
            if(is_null($declaration->stock_revendique) || $declaration->stock_revendique == 0)
            {
                $this->statut = DSClient::STATUT_VALIDE_PARTIEL;
                return;
            }
        }
    }
}