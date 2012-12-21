<?php
/**
 * Model for Generation
 *
 */

class Generation extends BaseGeneration {

  public function constructId() {
    $this->setDateEmission(date('YmdHis'));
    $this->setIdentifiant($this->type_document.'-'.$this->date_emission);
    $this->set_id('GENERATION-'.$this->identifiant);
    $this->setStatut(GenerationClient::GENERATION_STATUT_ENCOURS);
  }

  public function save() {
    $this->nb_documents = count($this->documents);
    if (count($this->fichiers)) {
      $this->setStatut(GenerationClient::GENERATION_STATUT_GENERE);
    }
    return parent::save();
  }
  
  public function __toString() {
     return GenerationClient::getInstance()->getDateFromIdGeneration($this->_id);
  }
  
}
