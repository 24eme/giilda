<?php

class revendicationComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new RevendicationEtablissementChoiceForm('INTERPRO-inter-loire',
              array('identifiant' => $this->identifiant));
    }
  }
}