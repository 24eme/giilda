<?php

class vracComponents extends sfComponents {

    public function executeFormEtablissementChoice() {
        if (!$this->identifiant) {
            $this->identifiant = null;
        }

        if (!$this->form) {
            $this->form = new VracEtablissementChoiceForm('INTERPRO-declaration', array('identifiant' => $this->identifiant));
        }
    }

    public function executeEtapes() {
        $this->etapes = VracConfiguration::getInstance()->getEtapes();
    }
}