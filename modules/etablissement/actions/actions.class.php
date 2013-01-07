<?php

class etablissementActions extends sfActions {

 public function executeAjout(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->etablissement = EtablissementClient::getInstance()->createEtablissement($this->societe);
        $this->contact = CompteClient::getInstance()->createCompte($this->societe);
        $this->etablissement->compte = $this->contact->_id;
        $this->processFormEtablissement($request);        
        $this->setTemplate('modification');    
    }    
    
    public function executeModification(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();
        $this->contact = $this->etablissement->getContact();
        $this->processFormEtablissement($request);
    }
    
     protected function processFormEtablissement(sfWebRequest $request) {
        $this->etablissementModificationForm = new EtablissementModificationForm($this->etablissement);
        $this->compteModificationForm = new CompteModificationEtbForm($this->contact);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->etablissementModificationForm->bind($request->getParameter($this->etablissementModificationForm->getName()));
            $this->compteModificationForm->bind($request->getParameter($this->compteModificationForm->getName()));
            if ($this->etablissementModificationForm->isValid() && $this->compteModificationForm->isValid()) {
                $this->etablissementModificationForm->save();
                $this->contact->origines->add($this->etablissement->_id,$this->etablissement->_id);
                $this->compteModificationForm->save();
                $this->redirect('societe_visualisation', array('identifiant' => $this->societe->identifiant));
            }
        }
    }
    
    public function executeVisualisation(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();
        $this->contact = $this->etablissement->getContact();        
    }

}
