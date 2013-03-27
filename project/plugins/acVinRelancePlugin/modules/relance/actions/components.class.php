<?php

class relanceComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new RelanceEtablissementChoiceForm('INTERPRO-inter-loire',
              array('identifiant' => $this->identifiant));
    }
  }
    
}