<?php

/**
 * Model for Alerte
 *
 */
class Alerte extends BaseAlerte {

    protected $config = null;

    public function __construct() {
        parent::__construct();
        if (!($this->isNew())) {
            $this->config = new AlerteConfig($this->type_alerte);
        }
    }

    public function getConfig() {
        if (!$this->config)
            $this->config = new AlerteConfig($this->type_alerte);
        return $this->config;
    }

    public function setTypeAlerte($value) {
        $this->_set('type_alerte', $value);
        $this->config = new AlerteConfig($this->type_alerte);
    }

    protected function constructId() {
        $this->_id = AlerteClient::getInstance()->buildId($this->type_alerte, $this->id_document);
    }

    public function getDocument($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return acCouchdbManager::getClient()->find($this->id_document, $hydrate);
    }

    public function setCreationDate($creation_date) {
        $this->date_creation = $creation_date;
    }

    public function open($date = null) {
        $this->updateStatut(AlerteClient::STATUT_NOUVEAU, 'Nouvelle alerte générée', $date);
    }

    public function getLastDateARelance() {
        $cpt = count($this->statuts) - 1;
        while ($cpt) {
            if ($this->statuts[$cpt]->statut == AlerteClient::STATUT_ARELANCER)
                return $this->statuts[$cpt]->date;
            $cpt--;
        }
        return null;
    }

    public function updateStatut($statut, $commentaire = null, $date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }
        $this->statuts->add(null, array('statut' => $statut, 'commentaire' => $commentaire, 'date' => $date));
        switch ($statut) {
            case AlerteClient::STATUT_A_RELANCER:
                $this->updateStatutRelance($date);
                break;
            case AlerteClient::STATUT_EN_ATTENTE_REPONSE:
                $this->date_relance = $this->getConfig()->getOptionDelaiDate('enattente_delai', $date);
                break;
        }
    }

    protected function updateStatutRelance($date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }
        if ($this->nb_relances >= $this->getConfig()->getOption('nb_relance')) {
            $this->updateStatut(AlerteClient::STATUT_EN_SOMMEIL, 'Alerte en sommeil', $date);
            return;
        }
        $this->nb_relances++;
        $this->date_relance = $this->getConfig()->getOptionDelaiDate('relance_delai', $date);

    }

    public function getStatut() {
        return $this->statuts->getLast();
    }

    public function isOpen() {

        return !$this->isFinished();
    }

    public function isStatutNouveau() {
        return $this->getStatut()->statut == AlerteClient::STATUT_NOUVEAU;
    }

    public function isFinished() {

        return in_array($this->getStatut()->statut, array(AlerteClient::STATUT_FERME, AlerteClient::STATUT_RESOLU));
    }

    public function isClosed() {
        return $this->getStatut()->statut == AlerteClient::STATUT_FERME;
    }
    
//    public function getLibelleForIdDocument() {
//        if(substr($this->id_document, 0 ,5) == 'VRAC-')
//                {
//            return 'Contrat N° '.VracClient::getInstance()->getLibelleContratNum(str_replace('VRAC-', '', $alerte->id_document));
//                }
//                return '';
//    }

}