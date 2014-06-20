<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class teledeclarationActions extends sfActions 
{   

  public function executeLogin(sfWebRequest $request) {
      
    if(sfConfig::has('app_login_no_cas') && sfConfig::get('app_login_no_cas')) {
        return $this->redirect('teledeclaration_login_no_cas');
    }
    $redirect = ($request->getParameter('referer')) ? $request->getParameter('referer') : $request->getUri();

    if($redirect == $this->generateUrl('teledeclaration_login', array(), true)) {
      $redirect = null;
    }

    if($redirect == $this->generateUrl('teledeclaration_logout', array(), true)) {
      $redirect = null;
    }

    if($redirect == $this->generateUrl('teledeclaration_forbidden', array(), true)) {
      $redirect = null;
    }

    if (!$redirect) {
      $redirect = $this->generateUrl('teledeclaration_monespace', array(), true);
    }
    return $this->redirect($redirect);
  }

  public function executeForbidden(sfWebRequest $request) {
  }

  public function executeLogout(sfWebRequest $request) {
    $this->setLayout(false);
    if (isset($_SERVER['HTTP_REFERER'])) {
      $referer = $_SERVER['HTTP_REFERER'];
    } else {
      $referer = $this->generateUrl('teledeclaration_monespace', array(), true);
    }
    $this->dest = $this->generateUrl('teledeclaration_login', array('referer' => $referer), true); //"http://".$_SERVER["SERVER_NAME"];
    if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != 'logout') {
      $this->dest = preg_replace('/http:\/\//', 'http://logout:logout@', $this->dest);
    }
  }
  
  public function executeLoginNoCas(sfWebRequest $request) {
        if (!(sfConfig::has('app_login_no_cas') && sfConfig::get('app_login_no_cas'))) {
            
            return $this->forward404Unless();
        }
        if($this->getUser()->hasCredential(TeledeclarationSecurityUser::CREDENTIAL_TELEDECLARATION)) {
            $compte = $this->getUser()->getCompte();
            return $this->redirect('teledeclaration_monespace',array('identifiant' => $compte->identifiant));
        }

        $this->getUser()->signOut();
        $this->form = new TeledeclarationCompteLoginForm(null, array('comptes_type' => array('Compte'), false));
        

        if ($request->isMethod(sfWebRequest::POST)) {
            
            $this->form->bind($request->getParameter($this->form->getName()));            
            if ($this->form->isValid()) {
                $idCompte = $this->form->process()->identifiant;
                $idSociete = $this->form->process()->getSociete()->getIdentifiant();                
                $this->getUser()->signIn($idCompte);
                $this->redirect('teledeclaration_monespace',array('identifiant' => $idCompte));
            }
        }
    }
    
    public function executeMonEspace(sfWebRequest $request) {
        $this->compte = CompteClient::getInstance()->findByIdentifiant($request['identifiant']);
        $this->secureVrac(VracSecurity::DROITS_TELEDECLARATION_VRAC, $this->vrac);
        if(!$this->compte){
            new sfException("Le compte $compte n'existe pas");
        }
        $this->societe = $this->compte->getSociete();
        $this->etablissements = $this->societe->getEtablissementsObj();
        $this->contratsEtablissements = VracClient::getInstance()->retrieveBySociete($this->societe->identifiant);
    }
    
    protected function secureVrac($droits, $vrac) {

        if(!VracSecurity::getInstance($this->getUser(), $vrac)->isAuthorized($droits)) {
            
            return $this->forwardSecure();
        }
    }

    protected function forwardSecure()
    {    
        $this->context->getController()->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));

        throw new sfStopException();
    }

}
