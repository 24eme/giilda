<?php

class factureComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new FactureEtablissementChoiceForm('INTERPRO-inter-loire',
              array('identifiant' => $this->identifiant));
    }
  }
  
  public function executeGenerationMasse() { 
    if (!$this->generationForm) {
      $this->generationForm = new FactureGenerationMasseForm(array('region' => $this->region));
    }
  }
    
}