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
    if($statut == GenerationClient::GENERATION_STATUT_ENATTENTE) {
      $this->message = "";
    }

    if($statut == GenerationClient::GENERATION_STATUT_ENCOURS) {
      $this->message = "";
    }

    if($statut == GenerationClient::GENERATION_STATUT_ENERREUR) {
      $this->message = "";
    }
    
    return $this->_set('statut', $statut);
  }

  public function reload() {
      $this->remove('fichiers');
      $this->add('fichiers');
      if(count($this->arguments) > 0) {
          $this->add('pregeneration_needed', 1);
      }
      $this->statut = GenerationClient::GENERATION_STATUT_ENATTENTE;
  }

  public function regenerate() {
      $this->somme = 0;
      $documents = array_merge($this->documents->toArray(true, false), $this->add('documents_regenerate')->toArray(true, false));
      $this->add('documents_regenerate', $documents); 
      $this->remove('documents');
      $this->add('documents');
      $this->reload();
  }
  
  public function __toString() {
     return GenerationClient::getInstance()->getDateFromIdGeneration($this->_id);
  }
  
}
