<?php

class revendicationComponents extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new RevendicationEtablissementChoiceForm('INTERPRO-inter-loire',
              array('identifiant' => $this->identifiant));
    }
  }

  public function executeEditionList() {
    if (isset($this->revendication)) {
      $this->odg = $this->revendication->odg;
      $this->campagne = $this->revendication->campagne;
    }
  }
}