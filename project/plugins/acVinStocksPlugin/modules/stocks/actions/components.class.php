<?php

class stocksComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new StocksEtablissementChoiceForm('INTERPRO-inter-loire',array('identifiant' => $this->identifiant));
    }
  }
}
