<?php


class sv12Components extends sfComponents {

  public function executeChooseEtablissement() {
    if (!$this->form) {
      $this->form = new Sv12EtablissementChoiceForm('INTERPRO-inter-loire',array('identifiant' => $this->identifiant));
    }
  }
}
