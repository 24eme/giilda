<?php

class globalComponents extends sfComponents
{

    public function executeNav(sfWebRequest $request)
    {
        $this->etablissement = null;
        if($this->getRoute() instanceof InterfaceEtablissementRoute) {
            $this->etablissement = $this->getRoute()->getEtablissement();
        }
        
        if($this->getUser()->hasTeledeclaration()) {
            $this->societe = $this->getUser()->getCompte()->getSociete();
        }
    }

    public function executeNavItem(sfWebRequest $request)
    {
        $this->actif = preg_match('/^'.$this->prefix.'/', $this->getRequest()->getParameter('module'));
    }

    public function getRoute()
    {
        return $this->getRequest()->getAttribute('sf_route');
    }
}
