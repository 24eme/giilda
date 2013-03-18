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
    $this->setStatut(GenerationClient::GENERATION_STATUT_ENATTENTE);
  }

  public function save() {
    $this->nb_documents = count($this->documents);
    if (count($this->fichiers) && $this->statut != GenerationClient::GENERATION_STATUT_ENERREUR) {
      $this->setStatut(GenerationClient::GENERATION_STATUT_GENERE);
    } 
    $this->setDateMaj(date('YmdHis'));
    return parent::save();
  }

  public function setStatut($statut) {
    $this->message = "";
    return $this->_set('statut', $statut);
  }
  
  public function __toString() {
     return GenerationClient::getInstance()->getDateFromIdGeneration($this->_id);
  }
  
}
