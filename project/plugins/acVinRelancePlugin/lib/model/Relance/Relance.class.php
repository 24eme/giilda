<?php
/**
 * Model for Relance
 *
 */

class Relance extends BaseRelance  {

    protected $declarant_document = null;
    protected $archivage_document = null;

    public function __construct() {
        parent::__construct();
        $this->initDocuments();
    }

    protected function initDocuments() {
        $this->declarant_document = new DeclarantDocument($this);
        
    }

    public function getEtablissement() 
    {
        return EtablissementClient::getInstance()->find($this->identifiant);
    }
    
    public function storeDatesCampagne($date_relance) {
        $this->date_creation = $date_relance;
        if (!$this->date_creation)
            $this->date_creation = date('Y-m-d');
    }
    
        public function constructIds($type_relance,$etb) {
        if (!$etb)
            throw new sfException("Pas d'etablissement attribué");
        
        $this->type_relance = $type_relance;
        $this->identifiant = $etb->identifiant;
        $this->region = $etb->getSociete()->getRegionViticole();
        $this->reference = RelanceClient::getInstance()->getNextRef($this->identifiant, $this->type_relance, $this->date_creation);
        $this->_id = RelanceClient::getInstance()->buildId($this->identifiant, $this->type_relance, $this->reference);
    }
    
     public function storeEmetteur() {
        $configs = sfConfig::get('app_relance_emetteur');
        if (!array_key_exists($this->region, $configs))
            throw new sfException(sprintf('Config %s not found in app.yml', $this->region));
        $this->emetteur = $configs[$this->region]; 
        $this->responsable_financier = sfConfig::get('app_relance_responsable_financier');
    }
    
    public function storeDeclarant() {
        $societe = $this->getSociete();
        $declarant = $this->declarant;
        $declarant->nom = $societe->raison_sociale;
        $declarant->num_tva_intracomm = $societe->no_tva_intracommunautaire;
        $declarant->adresse = $societe->getSiegeAdresses();
        $declarant->commune = $societe->siege->commune;
        $declarant->code_postal = $societe->siege->code_postal;
        $declarant->raison_sociale = $societe->raison_sociale;
    }
    
    public function getSociete() {
        return EtablissementClient::getInstance()->find($this->identifiant)->getSociete();
    }
    
    public function storeVerifications($alertes) {
        foreach ($alertes as $type_alerte => $alertes_t) {
            $this->storeVerificationForType($type_alerte, $alertes_t);
        }
    }
    
    
    public function storeVerificationForType($type_alerte, $alertes) {
        $verifications = $this->verifications->add($type_alerte);
        $verifications->storeDescriptionsForType($type_alerte,$alertes[0]->key[AlerteRelanceView::KEY_CAMPAGNE],$alertes[0]->value[AlerteRelanceView::VALUE_ID_DOC]);
        foreach ($alertes as $alerte) {
            $ligne = $verifications->lignes->add();
            $ligne->storeVerificationForAlerte($alerte);        
            if (!array_key_exists($alerte->id, $this->origines))
                        $this->origines->add($alerte->id, $alerte->id);
        }
        
        foreach ($this->origines as $alerte_id) {
            AlerteClient::getInstance()->updateStatutByAlerteId(AlerteClient::STATUT_EN_ATTENTE_GENERATION_RELANCE,AlerteClient::MESSAGE_AUTO_RELANCE,$alerte_id);
        }
    }         
    
}