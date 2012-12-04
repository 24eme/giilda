<?php

class compteActions extends sfActions
{
    
    public function executeNouveau(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $this->societe = SocieteClient::getInstance()->find($this->compte->id_societe);
        $this->compteForm = new CompteExtendedModificationForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->compteForm->bind($request->getParameter($this->compteForm->getName()));
            if ($this->compteForm->isValid()) {
                $this->compteForm->save();
                $this->redirect('societe_visualisation',array('identifiant' => $this->societe->identifiant));
            }
        }
    }
    
    public function executeModification(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $this->compteModificationForm = new CompteModificationForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->compteModificationForm->bind($request->getParameter($this->compteModificationForm->getName()));
           if ($this->compteModificationForm->isValid()) {
                $this->compteModificationForm->save();
                
            }
        }
    }
    

}
