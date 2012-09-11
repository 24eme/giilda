<?php
/**
 * Model for Facture
 *
 */

class Generation extends BaseGeneration {
  const GENERATION_STATUT_ENCOURS = "En cours";
  const GENERATION_STATUT_GENERE = "Généré";

  public function constructId() {
    $this->setDateEmission(date('YmdH:i'));
    $this->setIdentifiant($this->type_document.'-'.$this->date_emission);
    $this->set_id('GENERATION-'.$this->identifiant);
    $this->setStatut(self::GENERATION_STATUT_ENCOURS);
  }
  
  public function save() {
    $this->nb_documents = count($this->documents);
    parent::save();
  }
}