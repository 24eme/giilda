<?php

class globalComponents extends sfComponents {

    public function executeNav(sfWebRequest $request) {
        $this->etablissement = null;
        if ($this->getRoute() instanceof InterfaceEtablissementRoute) {
            $this->etablissement = $this->getRoute()->getEtablissement();
        }
    }

    public function executeNavItem(sfWebRequest $request) {
        $this->actif = preg_match('/^' . $this->prefix . '/', $this->getRequest()->getParameter('module'));
    }

    public function executeBlocks(sfWebRequest $request) {
        $this->etablissement = null;
        if ($this->getRoute() instanceof InterfaceEtablissementRoute) {
            $this->etablissement = $this->getRoute()->getEtablissement();
        }
        if($request->getParameter('identifiant')){
            $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($request->getParameter('identifiant'));
        }
    }

    public function executeBlocksTeledeclaration(sfWebRequest $request) {
      $this->teledeclaration = false;
      $this->teledeclaration_vrac = false;
      $this->teledeclaration_drm = false;
      $this->etablissementPrincipal = null;
      if($this->getUser()->hasCredential('teledeclaration')){
        $this->teledeclaration = true;
        $this->etablissementPrincipal = $this->getUser()->getCompte()->getSociete()->getEtablissementPrincipal();
      }
      if($this->getUser()->hasCredential('teledeclaration_vrac')){
        $this->teledeclaration_vrac = true;
      }
      if($this->getUser()->hasCredential('teledeclaration_drm')){
        $this->teledeclaration_drm = true;
      }
        $this->etablissementPrincipal = $this->getUser()->getCompte()->getSociete()->getEtablissementPrincipal();

    }

    public function executeBlockItem(sfWebRequest $request) {
        $this->actif = preg_match('/^' . $this->prefix . '/', $this->getRequest()->getParameter('module'));
    }


    public function getRoute() {
        return $this->getRequest()->getAttribute('sf_route');
    }

}
