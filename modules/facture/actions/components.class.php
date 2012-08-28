<?php

class factureComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new FactureEtablissementChoiceForm(array('identifiant' => $this->identifiant));
    }
  }
}