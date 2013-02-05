<?php

/**
 * Description of class AlerteGeneration
 * @author mathurin
 */
abstract class AlerteGeneration {

    protected $dev = false;
    protected $config = null;

    public function __construct() {
        $this->config = new AlerteConfig($this->getTypeAlerte());
    }

    public function getConfig() {
        return $this->config;
    }

    public function isDev() {

        return $this->dev === true;
    }

    public function setModeDev($mode) {
        $this->dev = $mode;
    }

    public function getAlertesOpen() {
        return AlerteHistoryView::getInstance()->findByTypeAndStatuts($this->getTypeAlerte(), AlerteClient::$statutsOpen);
    }

    public function getAlertesRelancable() {
        return AlerteHistoryView::getInstance()->findByTypeAndStatuts($this->getTypeAlerte(), AlerteClient::$statutsRelancable);
    }

    public function getAlerte($id_document) {
        return AlerteClient::getInstance()->find(AlerteClient::getInstance()->buildId($this->getTypeAlerte(), $id_document));
    }

    public function createOrFind($id_document) {
        $alerte = $this->getAlerte($id_document);
        if (!$alerte) {
            $alerte = new Alerte();
            $alerte->setCreationDate($this->getDate());
            $alerte->type_alerte = $this->getTypeAlerte();
            $alerte->id_document = $id_document;
            $alerte->nb_relances = 0;
            $alerte->date_relance = $this->getConfig()->getOptionDelaiDate('relance_delai', $alerte->date_creation);
            $this->storeDatasRelance($alerte);
        }
        return $alerte;
    }
    
    public function getDate() {

        return date('Y-m-d');
    }

    public abstract function getTypeAlerte();

    protected abstract function storeDatasRelance(Alerte $alerte);

    public abstract function creations();

    public function updates() {
        foreach ($this->getAlertesRelancable() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            $relance = Date::supEqual($this->getDate(), $alerte->date_relance);
            if ($relance) {
                $alerte->updateStatut(AlerteClient::STATUT_ARELANCER, null, $this->getDate());
                $alerte->save();
            }
        }
    }
}
