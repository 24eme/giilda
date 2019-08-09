<?php

class globalComponents extends sfComponents
{

    public function executeNav(sfWebRequest $request)
    {
        $this->etablissement = isset($this->etablissement) ? $this->etablissement : null;
        if($this->getRoute() instanceof InterfaceEtablissementRoute && !$this->etablissement) {
            $this->etablissement = $this->getRoute()->getEtablissement();
        }

        $this->societe = isset($this->societe) ? $this->societe : null;

        if($this->getUser()->hasTeledeclaration() && !$this->societe) {
            $this->societe = $this->getUser()->getCompte()->getSociete();
        }

        if($this->getRoute() instanceof InterfaceSocieteRoute && !$this->societe) {
            $this->societe = $this->getRoute()->getSociete();
        }

        if($this->societe && !$this->etablissement) {
            $this->etablissement = $this->societe->getEtablissementPrincipal();
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
