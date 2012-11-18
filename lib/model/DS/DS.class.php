<?php

/**
 * Model for DS
 *
 */
class DS extends BaseDS implements InterfaceDeclarantDocument, InterfaceArchivageDocument {

    protected $declarant_document = null;
    protected $archivage_document = null;

    public function __construct() {
        parent::__construct();
        $this->declarant_document = new DeclarantDocument($this);
        $this->archivage_document = new ArchivageDocument($this);
    }

    public function constructId() {
        if($this->statut == null) {
            $this->statut = DSClient::STATUT_A_SAISIR;
        }
        $this->set('_id', DSClient::getInstance()->buildId($this->identifiant, $this->periode));
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

    public function updateProduits() {
        $drm = $this->getLastDRM();
        if ($drm) {
            $produits = $drm->getDetails();

            foreach ($produits as $produit) {
                $produitDs = $this->declarations->add($produit->getHashForKey());
                $produitDs->updateProduit($produit);
            }
        }
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
            if (is_null($declaration->stock_revendique) || $declaration->stock_revendique == 0) {
                $this->statut = DSClient::STATUT_VALIDE_PARTIEL;
                return;
            }
        }
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

    public function getDateArchivage() {

        return $this->_get('date_archivage');
    }

    public function isArchivageCanBeSet() {

        return $this->isStatutValide();
    }

    public function getDateArchivageLimite() {

        return ConfigurationClient::getInstance()->buildDateFinCampagne($this->date_archivage);
    }
    
    /*** FIN ARCHIVAGE ***/
}