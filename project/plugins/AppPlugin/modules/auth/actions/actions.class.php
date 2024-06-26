<?php

class authActions extends sfActions {

    public function executeLogin(sfWebRequest $request) {
        if (sfConfig::get("app_auth_mode") != 'NO_CAS') {


            return $this->forward404();
        }

        $this->form = new TeledeclarationCompteLoginForm(null, array('comptes_type' => array('Compte'), false));


        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {

            return sfView::SUCCESS;
        }

        $idCompte = $this->form->process()->identifiant;
        $idSociete = $this->form->process()->getSociete()->getIdentifiant();
        $this->getUser()->signInOrigin($this->form->getValue("login"));

        return $this->redirect('common_homepage');
    }

    public function executeLogout(sfWebRequest $request) {
       $this->getUser()->signOutOrigin();
       $urlBack = $this->generateUrl('common_homepage', array(), true);

       if($request->getParameter('url')) {
           $urlBack = $request->getParameter('url');
       }

       if (sfConfig::get("app_auth_mode") == 'CAS') {
           acCas::processLogout($urlBack);
       }

       return $this->redirect($urlBack);
    }

    public function executeUsurpation(sfWebRequest $request) {
        $compte = CompteClient::getInstance()->find("COMPTE-".$request->getParameter('identifiant'));
        $login = $compte->getSociete()->getMasterCompte()->login;
        if($compte->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
            $login = $compte->login;
        }

        $this->getUser()->usurpationOn($login, $request->getReferer());

        return $this->redirect('common_homepage');
    }

    public function executeDeconnexionUsurpation(sfWebRequest $request) {
        $url_back = $this->getUser()->usurpationOff();

        if ($url_back) {

            return $this->redirect($url_back);
        }

        $this->redirect('common_homepage');
    }


    public function executeForbidden(sfWebRequest $request) {

    }

}
