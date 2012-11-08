<?php

/**
 * Description of class AlerteGeneration
 * @author mathurin
 */
abstract class AlerteGeneration {

    protected $dev = false;
    protected $config = null;
    public $date = '2012-12-08';

    public function __construct() {
        $configs = sfConfig::get('app_alertes_generations');
        if (!array_key_exists($this->getTypeAlerte(), $configs))
            throw new sfException(sprintf('Config %s not found in app.yml', $this->getTypeAlerte()));
        $this->config = $configs[$this->getTypeAlerte()];
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

    public function createOrFind($id_document, $identifiant, $nom) {
        $alerte = $this->getAlerte($id_document);
        if (!$alerte) {
            $alerte = new Alerte();
            $alerte->type_alerte = $this->getTypeAlerte();
            $alerte->id_document = $id_document;
            $alerte->identifiant = $identifiant;
            $alerte->declarant_nom = $nom;
        }
        return $alerte;
    }

    public function getConfigOption($field) {
        if (!isset($this->config[$field]))
            return null;
        return $this->config[$field];
    }

    public function getConfigOptionDate($field) {
        $dates = array();
        preg_match('/^([0-9]+)\/([0-9]+)/', $this->getConfigOption($field), $dates);
        return sprintf('%04d-%02d-%02d', date('Y'), $dates[2], $dates[1]);
    }

    public function getConfigOptionDelaiDate($field, $date = null) {
        if (!$date)
            $date = date('Y-m-d');
        $delai = $this->getConfigOption($field);
        if (!$delai) {
            return null;
        }
        return Date::addDelaiToDate($delai, $date);
    }

    public function getDate() {

        return $this->date; // return date('Y-m-d');
    }

    public abstract function getTypeAlerte();

    public abstract function creations();

    public abstract function updates();
}
