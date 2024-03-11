<?php

class EtablissementRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    protected $etablissement = null;
    protected $campagne = null;

    protected function getObjectForParameters($parameters = null) {
        $this->etablissement = EtablissementClient::getInstance()->find($parameters['identifiant']);
        if (!$this->etablissement) {
            throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page (pas d'etablissement)");
        }
        $myUser = sfContext::getInstance()->getUser();
        $compteUser = $myUser->getCompte();
        if ($myUser->hasTeledeclaration() && !$myUser->isAdmin() && $compteUser->identifiant != $this->getEtablissement()->getSociete()->getMasterCompte()->identifiant) {

            throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page");
        }

        $module = sfContext::getInstance()->getRequest()->getParameterHolder()->get('module');

        if($campagne = sfContext::getInstance()->getRequest()->getParameterHolder()->get('campagne',null)){
          $this->campagne = $campagne;
        }
        sfContext::getInstance()->getResponse()->setTitle(strtoupper($module).' - '.$this->etablissement->nom);
        return $this->etablissement;
    }

    protected function doConvertObjectToArray($object) {
        if (!$object) {
            throw new sfException("object from parameter should not be null");
        }
        return array("identifiant" => $object->getIdentifiant());
    }

    public function getEtablissement() {

	    if (!$this->etablissement) {
            $this->getObject();
      	}

	    return $this->etablissement;
    }

    public function getCampagne(){
      return $this->campagne;
    }
}
