<?php

class factureActions extends sfActions {
  public function executeIndex(sfWebRequest $request) {
    $this->form = new EtablissementChoiceForm();
    
  }
}