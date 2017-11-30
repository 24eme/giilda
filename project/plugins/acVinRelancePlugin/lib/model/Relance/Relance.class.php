<?php

/**
 * Model for Relance
 *
 */
class Relance extends BaseRelance {

    protected $declarant_document = null;
    protected $archivage_document = null;

    public function __construct() {
        parent::__construct();
        $this->initDocuments();
    }

    protected function initDocuments() {
        $this->declarant_document = new DeclarantDocument($this);
    }

    public function getEtablissement() {
        return EtablissementClient::getInstance()->find($this->identifiant);
    }

    public function storeDatesCampagne($date_relance) {
        $this->date_creation = $date_relance;
        if (!$this->date_creation)
            $this->date_creation = date('Y-m-d');
    }

    public function constructIds($type_relance, $etb) {
        if (!$etb)
            throw new sfException("Pas d'etablissement attribué");

        $this->type_relance = $type_relance;
        $this->identifiant = $etb->identifiant;
        $this->region = $etb->getSociete()->getRegionViticole(false);
        $this->reference = RelanceClient::getInstance()->getNextRef($this->identifiant, $this->type_relance, $this->date_creation);
        $this->_id = RelanceClient::getInstance()->buildId($this->identifiant, $this->type_relance, $this->reference);
    }

    public function storeEmetteur() {
        $configs = sfConfig::get('app_relance_emetteur');
        if (!array_key_exists($this->region, $configs))
            throw new sfException(sprintf('Config %s not found in app.yml', $this->region));
        $this->emetteur = $configs[$this->region];
        $this->responsable_economique = sfConfig::get('app_relance_responsable_economique');
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
        foreach ($alertes as $alerte) {
            $type_relance = $alerte->key[AlerteRelanceView::KEY_TYPE_RELANCE];
            if (!$this->verifications->exist($type_relance)) {
                $verifications = $this->verifications->add($type_relance);
                $verifications->storeDescriptionsForType($type_relance, $alerte->key[AlerteRelanceView::KEY_CAMPAGNE]);
            }
            $this->storeVerificationForType($alerte);
        }
    }

    public function storeVerificationForType($alerte) {

        $type_relance = $alerte->key[AlerteRelanceView::KEY_TYPE_RELANCE];
        $ligne = $this->verifications->$type_relance->lignes->add();
        $ligne->storeVerificationForAlerte($alerte);
        if (!array_key_exists($alerte->id, $this->origines))
            $this->origines->add($alerte->id, $alerte->id);

        $newStatut = $alerte->key[AlerteRelanceView::KEY_STATUT];
        $msg = "";
        $date_relance_ar = null;
        if ($newStatut == AlerteClient::STATUT_A_RELANCER) {
            $newStatut = AlerteClient::STATUT_EN_ATTENTE_REPONSE;
            $date_relance_ar = Date::addDelaiToDate("+1 month", AlerteDateClient::getInstance()->getDate());
            $msg = AlerteClient::MESSAGE_AUTO_RELANCE;
        }
        if ($newStatut == AlerteClient::STATUT_A_RELANCER_AR) {
            $newStatut = AlerteClient::STATUT_EN_ATTENTE_REPONSE_AR;
            $msg = AlerteClient::MESSAGE_AUTO_RELANCE_AR;
        }
        AlerteClient::getInstance()->updateStatutByAlerteId($newStatut, $msg, $alerte->id, null, $date_relance_ar);
    }

}
