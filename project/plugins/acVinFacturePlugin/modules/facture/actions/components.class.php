<?php

class factureComponents extends sfComponents {

    public function executeChooseSociete() {
        if (!$this->form) {
            $this->form = new FactureSocieteChoiceForm('INTERPRO-declaration', array('identifiant' => $this->identifiant));
        }
    }

    public function executeGenerationMasse() {
        if (!$this->generationForm) {
            $this->generationForm = new FactureGenerationForm(null, array('export'=> true));
            $this->massive = true;
        }
    }

}
