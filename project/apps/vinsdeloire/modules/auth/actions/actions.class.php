<?php

class authActions extends sfActions 
{

  public function executeLogin(sfWebRequest $request) {
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
      $this->getUser()->signIn($this->form->getValue("compte"));
      $this->redirect('vrac_societe',array('identifiant' => $idCompte));
  }

  public function executeLogout(sfWebRequest $request) {
      return AuthFilter::logout();
  }

  public function executeForbidden(sfWebRequest $request) {

  }

}