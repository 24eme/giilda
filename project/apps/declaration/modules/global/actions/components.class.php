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
    
    public function executeBlockItem(sfWebRequest $request) {
        $this->actif = preg_match('/^' . $this->prefix . '/', $this->getRequest()->getParameter('module'));
    }
    

    public function getRoute() {
        return $this->getRequest()->getAttribute('sf_route');
    }

}
