<?php

class societeComponents extends sfComponents {

  public function executeChooseSociete() {
    if (!$this->form) {
      $this->form = new SocieteChoiceForm('INTERPRO-inter-loire',
              array('identifiant' => $this->identifiant));
    }
  }
    
}