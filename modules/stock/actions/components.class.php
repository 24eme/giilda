<?php

class stockComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new StockEtablissementChoiceForm('INTERPRO-inter-loire',
              array('identifiant' => $this->identifiant));
    }
  }
    
}