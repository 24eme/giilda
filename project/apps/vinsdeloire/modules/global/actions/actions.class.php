<?php

class globalActions extends sfActions {

    public function executeError500(sfWebRequest $request) {
        $this->exception = $request->getParameter('exception');
        if (sfConfig::get('app_auth_mode') != 'HTTP_AD') {
            $this->setTemplate('error500Teledeclaration', 'global');
        }
    }

    public function executeError404(sfWebRequest $request) {
        $this->exception = $request->getParameter('exception');
    }

    public function executeHome(sfWebRequest $request) {
        
        if ($this->getUser()->hasCredential('transactions')) {
            return $this->redirect('vrac');
        }
        
        if ($this->getUser()->hasObservatoire() && !$this->getUser()->hasTeledeclarationVrac()) {
            $this->redirect(sfConfig::get('app_observatoire_url'));
        }
        
        if (!$this->getUser()->hasCredential('operateur')) {

            return $this->redirect('vrac_societe', array("identifiant" => $this->getUser()->getCompte()->identifiant));
        }


        return $this->redirect('societe');
    }

}
