<?php

class factureComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new EtablissementChoiceForm(array('identifiant' => $this->identifiant));
    }
  }
}