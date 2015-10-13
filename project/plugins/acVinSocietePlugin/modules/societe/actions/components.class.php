<?php

class societeComponents extends sfComponents {

    public function executeChooseSociete() {
        if (!$this->form) {
            $this->form = new SocieteChoiceForm('INTERPRO-declaration',
                            array('identifiant' => $this->identifiant));
        }
    }

    public function executeGetInterlocuteurs() {
        $this->no_link = false;
        if($this->getUser()->hasOnlyCredentialDRM()){
             $this->no_link = true;
        }
        $this->contacts = null;
        if(!isset($this->withSuspendus)) $this->withSuspendus = false;
        if ($this->getRoute() instanceof InterfaceEtablissementRoute) {
            $etablissement = $this->getRoute()->getEtablissement();
            if ($etablissement)
                $this->contacts = SocieteClient::getInstance()->getInterlocuteursWithOrdre($etablissement->id_societe, $this->withSuspendus);
        }
        if ($this->getRoute() instanceof SocieteRoute) {
            $societe = $this->getRoute()->getSociete();
            $this->contacts = SocieteClient::getInstance()->getInterlocuteursWithOrdre($societe->identifiant, $this->withSuspendus);
        }
         if ($this->getRoute() instanceof CompteRoute) {
            $compte = $this->getRoute()->getCompte();
            $this->contacts = SocieteClient::getInstance()->getInterlocuteursWithOrdre($compte->id_societe, $this->withSuspendus);
        }
    }
    
     public function executeGetInterlocuteursWithSuspendus() {
         $this->withSuspendus = true;
         $this->executeGetInterlocuteurs();         
     }

    public function getRoute() {
        return $this->getRequest()->getAttribute('sf_route');
    }

}