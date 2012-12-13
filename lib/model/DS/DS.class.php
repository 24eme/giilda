<?php

/**
 * Model for DS
 *
 */
class DS extends BaseDS implements InterfaceDeclarantDocument, InterfaceArchivageDocument {

    protected $declarant_document = null;
    protected $archivage_document = null;

    public function  __construct() {
        parent::__construct();   
        $this->initDocuments();
    }

    public function __clone() {
        parent::__clone();
        $this->initDocuments();
    }   

    protected function initDocuments() {
        $this->declarant_document = new DeclarantDocument($this);
        $this->archivage_document = new ArchivageDocument($this);
    }

    public function constructId() {
        if($this->statut == null) {
            $this->statut = DSClient::STATUT_A_SAISIR;
        }
        $this->set('_id', DSClient::getInstance()->buildId($this->identifiant, $this->periode));
    }
    
    public function getCampagne() {

        return $this->_get('campagne');
    }

    public function setDateStock($date_stock) {
        $this->date_echeance = Date::getIsoDateFinDeMoisISO($date_stock, 2);
        $this->periode = DSClient::getInstance()->buildPeriode($date_stock);

        return $this->_set('date_stock', $date_stock);
    }

    public function setPeriode($periode) {
        $this->campagne = DSClient::getInstance()->buildCampagne($periode);

        return $this->_set('periode', $periode);
    }

    public function getLastDRM() {
        
        return DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->identifiant, $this->campagne);
    }

    public function getLastDS() {
        
        return DSClient::getInstance()->findLastByIdentifiant($this->identifiant);
    }

    public function updateProduits() {
        $drm = $this->getLastDRM();
        if ($drm) {
           
           return $this->updateProduitsFromDRM($drm); 
        }
        $ds = $this->getLastDS();
        if ($ds) {
            
           return $this->updateProduitsFromDS($ds); 
        }
    }

    protected function updateProduitsFromDRM($drm) {
         $produits = $drm->getProduitsDetails();

        foreach ($produits as $produit) {
            $produitDs = $this->declarations->add($produit->getHashForKey());
            $produitDs->updateProduit($produit);
        }
    }

    protected function updateProduitsFromDS($ds) {
        foreach ($ds->declarations as $hash => $produit) {
            if (!$produit->isActif()) {
                
                continue;
            }
            $nouveau_produit = $this->declarations->add($hash, $produit);

            $nouveau_produit->stock_initial = null;
            $nouveau_produit->stock_revendique = null;
        }
    }
    
    public function isStatutValide() {
        return $this->statut === DSClient::STATUT_VALIDE;
    }

    public function isStatutASaisir() {
        return $this->statut === DSClient::STATUT_A_SAISIR;
    }

    public function updateStatut() {
        $this->statut = DSClient::STATUT_VALIDE;
    }

    protected function preSave() {
        $this->archivage_document->preSave();
    }

    /*** DECLARANT ***/

    public function getEtablissementObject() {
        return $this->declarant_document->getEtablissementObject();
    }

    public function storeDeclarant() {
        $this->declarant_document->storeDeclarant();
    }

    /*** FIN DECLARANT ***/

    /*** ARCHIVAGE ***/

     public function getNumeroArchive() {

        return $this->_get('numero_archive');
    }

    public function isArchivageCanBeSet() {

        return $this->isStatutValide();
    }

    /*** FIN ARCHIVAGE ***/
    
    public function getDepartement() 
    {
        if($this->declarant->code_postal )  {
          return substr($this->declarant->code_postal, 0, 2);
        }
        return null;
    }

    public function getEtablissement() 
    {
        return EtablissementClient::getInstance()->find($this->identifiant);
    }
    
    public function getInterpro() 
    {
      	if ($this->getEtablissement()) {
         	return $this->getEtablissement()->getInterproObject();
     	}
    }
}