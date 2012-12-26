<?php

class societeComponents extends sfComponents {

    public function executeChooseSociete() {
        if (!$this->form) {
            $this->form = new SocieteChoiceForm('INTERPRO-inter-loire',
                            array('identifiant' => $this->identifiant));
        }
    }

    public function executeGetInterlocuteurs() {
        $this->contacts = null;
        if ($this->getRoute() instanceof InterfaceEtablissementRoute) {
            $etablissement = $this->getRoute()->getEtablissement();
            if ($etablissement)
                $this->contacts = SocieteClient::getInstance()->getInterlocuteursWithOrdre($etablissement->id_societe);
        }
        if ($this->getRoute() instanceof SocieteRoute) {
            $societe = $this->getRoute()->getSociete();
            $this->contacts = SocieteClient::getInstance()->getInterlocuteursWithOrdre($societe->identifiant);
        }
         if ($this->getRoute() instanceof CompteRoute) {
            $compte = $this->getRoute()->getCompte();
            $this->contacts = SocieteClient::getInstance()->getInterlocuteursWithOrdre($compte->id_societe);
        }
    }

    public function getRoute() {
        return $this->getRequest()->getAttribute('sf_route');
    }

}