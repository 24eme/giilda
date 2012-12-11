<?php

class etablissementActions extends sfActions {

    public function executeNouveau(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = SocieteClient::getInstance()->find($this->etablissement->id_societe);
        $this->contact = $this->etablissement->getContact();
        $this->etablissementModificationForm = new EtablissementModificationForm($this->etablissement);
        $this->compteModificationForm = new CompteModificationEtbForm($this->contact);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->etablissementModificationForm->bind($request->getParameter($this->etablissementModificationForm->getName()));
            $this->compteModificationForm->bind($request->getParameter($this->compteModificationForm->getName()));
            if ($this->etablissementModificationForm->isValid() && $this->compteModificationForm->isValid()) {
                $this->etablissementModificationForm->save();
                $this->compteModificationForm->save();
                $this->redirect('societe_visualisation', array('identifiant' => $this->societe->identifiant));
            }
        }
    }

    public function executeModification(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->contact = $this->etablissement->getContact();

        $this->etablissementModificationForm = new EtablissementModificationForm($this->etablissement);
        $this->compteModificationForm = new CompteModificationForm($this->contact);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->etablissementModificationForm->bind($request->getParameter($this->etablissementModificationForm->getName()));
            $this->compteModificationForm->bind($request->getParameter($this->compteModificationForm->getName()));
            if ($this->etablissementModificationForm->isValid() && $this->compteModificationForm->isValid()) {
                
                $this->etablissementModificationForm->save();
                $this->compteModificationForm->save();
            }
        }
    }
    
    public function executeVisualisation(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->contact = $this->etablissement->getContact();        
    }

}
