<?php

/**
 * Model for DS
 *
 */
class DS extends BaseDS implements InterfaceDeclarantDocument {

    protected $declarant_document = null;

    public function  __construct() {
        parent::__construct();   
        $this->declarant_document = new DeclarantDocument($this);
    }

    public function constructId() {
        $this->statut = DSClient::STATUT_A_SAISIR;
        $this->campagne = DSClient::getInstance()->buildCampagne($this->periode);
        $this->set('_id', DSClient::getInstance()->buildId($this->identifiant, 
                                                            $this->periode));
    }

    public function buildCampagne($periode) {
        preg_match('/^([0-9]{4})-([0-9]{2})$/', $periode, $matches);

        return sprintf('%d-%d', $matches[1], $matches[2]);
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
        return $this->declarant_document->getEtablissementObject();
    }

    public function storeDeclarant() {
        $this->declarant_document->storeDeclarant();
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