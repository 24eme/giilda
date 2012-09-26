<?php
/**
 * Model for Generation
 *
 */

class Generation extends BaseGeneration {
  const GENERATION_STATUT_ENCOURS = "En cours";
  const GENERATION_STATUT_GENERE = "Généré";

  public function constructId() {
    $this->setDateEmission(date('YmdHis'));
    $this->setIdentifiant($this->type_document.'-'.$this->date_emission);
    $this->set_id('GENERATION-'.$this->identifiant);
    $this->setStatut(self::GENERATION_STATUT_ENCOURS);
  }

  public function save() {
    $this->nb_documents = count($this->documents);
    if (count($this->fichiers)) {
      $this->setStatut(self::GENERATION_STATUT_GENERE);
    }
    parent::save();
  }
  
  public function __toString() {
     return GenerationClient::getInstance()->getDateFromIdGeneration($this->_id);
  }
  
}
