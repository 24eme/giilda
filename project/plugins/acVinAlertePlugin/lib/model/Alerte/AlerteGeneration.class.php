<?php

/**
 * Description of class AlerteGeneration
 * @author mathurin
 */
abstract class AlerteGeneration {

    protected $dev = false;
    protected $config = null;

    
    public function __construct() {
        $configs = sfConfig::get('app_alertes_generations');
        if(!array_key_exists($this->getTypeAlerte(), $configs))
            throw new sfException(sprintf('Config %s not found in app.yml',$this->getTypeAlerte()));
        $this->config = $configs[$this->getTypeAlerte()];
    }

    public function isDev() {

        return $this->dev === true;
    }

    public function setModeDev($mode) {
        $this->dev = $mode;
    }

    public function getAlertesOpen() {
        return AlerteHistoryView::getInstance()->findByTypeAndStatuts($this->getTypeAlerte(),  AlerteClient::$statutsOpen);
    }
    
    public function getAlertesRelancable() {
        return AlerteHistoryView::getInstance()->findByTypeAndStatuts($this->getTypeAlerte(),  AlerteClient::$statutsRelancable);
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

    public function getConfigOption($field){
        if(!isset($this->config[$field])) return null;
        return $this->config[$field];
    }
    
    public function getConfigOptionDate($field) {
        preg_match('/^([0-9]+)/([0-9]+)/', $this->getConfigOption($field), $dates);
        
        return sprintf('%02d-%02d-%04d', $dates[1], $dates[2], date('y')); 
    }
    
    public function getConfigOptionDelaiDate($field, $date = 'Y-m-d') {
        $delai = $this->getConfigOption($field);
        if (!$delai) {
            return null;
        }
        
        return date('Y-m-d', strtotime($delai, $date));
    }
    
    public abstract function getTypeAlerte();

    public abstract function creations();
    
    public abstract function updates();
}
