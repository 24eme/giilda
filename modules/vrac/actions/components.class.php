<?php

class vracComponents extends sfComponents {

    public function executeFormEtablissementChoice() {
        if (!$this->identifiant) {
            $this->identifiant = null;
        }

        if (!$this->form) {
            $this->form = new VracEtablissementChoiceForm(array('identifiant' => $this->identifiant));
        }
    }
}