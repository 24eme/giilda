<?php

class authActions extends sfActions 
{

  public function executeLogin(sfWebRequest $request) {
      if(sfConfig::get("app_auth_mode") != 'NO_CAS') {
            
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
      
      $this->redirect('vrac_societe',array('identifiant' => $idCompte));
  }

  public function executeLogout(sfWebRequest $request) {
      $this->getUser()->signOutOrigin();
      $urlBack = $this->generateUrl('homepage', array(), true);

      if(sfConfig::get("app_auth_mode") == 'CAS') {
          acCas::processLogout($urlBack);
      }

      return $this->redirect('homepage');
  }

  public function executeForbidden(sfWebRequest $request) {

  }

}