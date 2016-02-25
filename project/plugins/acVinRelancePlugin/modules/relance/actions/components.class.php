<?php

class relanceComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new RelanceEtablissementChoiceForm('INTERPRO-declaration',
              array('identifiant' => $this->identifiant));
    }
  }
    public function executeGenerationMasse() { 
    if (!$this->generationForm) {
      $this->generationForm = new RelanceGenerationMasseForm(array_keys(AlerteClient::$alertes_libelles));
    }
  }
    
}