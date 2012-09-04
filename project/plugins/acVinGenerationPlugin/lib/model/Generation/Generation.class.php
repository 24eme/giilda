<?php
/**
 * Model for Facture
 *
 */

class Generation extends BaseGeneration {
    public function constructId() {
        
        $this->setDateEmission(date('YmdH:i'));
        $this->setIdentifiant($this->type_document.'-'.$this->date_emission);
        $this->set_id('GENERATION-'.$this->identifiant);
    }
    
    public function save() {
        $this->nb_documents = count($this->documents);
        parent::save();
    }
}