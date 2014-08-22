<?php

class globalActions extends sfActions {

    public function executeError500(sfWebRequest $request) {
        $this->exception = $request->getParameter('exception');
        if ($this->getUser()->hasTeledeclarationVrac()) {
            $this->setTemplate('error500Teledeclaration','global');
        }
    }

    public function executeHome(sfWebRequest $request) {
        if ($this->getUser()->hasCredential('transactions')) {

            return $this->redirect('vrac');
        }

        if (!$this->getUser()->hasCredential('operateur')) {

            return $this->redirect('vrac_societe', array("identifiant" => $this->getUser()->getCompte()->identifiant));
        }

        return $this->redirect('societe');
    }

}
