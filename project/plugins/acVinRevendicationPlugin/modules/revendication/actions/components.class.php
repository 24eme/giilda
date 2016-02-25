<?php

class revendicationComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new RevendicationEtablissementChoiceForm('INTERPRO-declaration',
              array('identifiant' => $this->identifiant));
    }
  }
}