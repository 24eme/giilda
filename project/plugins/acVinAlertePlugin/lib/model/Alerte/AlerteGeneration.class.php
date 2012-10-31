<?php

/**
 * Description of class AlerteGeneration
 * @author mathurin
 */
abstract class AlerteGeneration {

    protected $dev = false;

    public function __construct() {
        
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


    public abstract function getTypeAlerte();

    public abstract function creations();
    
    public abstract function updates();
}
