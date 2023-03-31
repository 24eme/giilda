<?php

class factureComponents extends sfComponents {

    public function executeChooseSociete() {
        if (!$this->form) {
            $this->form = new FactureSocieteChoiceForm('INTERPRO-declaration', array('identifiant' => $this->identifiant));
        }
    }

}
