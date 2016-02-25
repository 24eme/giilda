<?php

class dsComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new DSEtablissementChoiceForm('INTERPRO-declaration',
              array('identifiant' => $this->identifiant));
    }
  }
    
}