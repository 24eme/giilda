<?php

class compteActions extends sfActions
{
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
