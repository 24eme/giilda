<?php

class factureActions extends sfActions {
  public function executeIndex(sfWebRequest $request) {
    $this->form = new EtablissementChoiceForm();
    if ($request->isMethod(sfWebRequest::POST)) {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid()) {
	return $this->redirect('facture_etablissement', $this->form->getEtablissement());
      }
    }
  }
  public function executeMonEspace(sfWebRequest $resquest) {
    $this->etablissement = $this->getRoute()->getEtablissement();
    $this->factures = FactureClient::getInstance()->findByEtablissement($this->etablissement);
  }
}