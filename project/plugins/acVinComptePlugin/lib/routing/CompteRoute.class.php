<?php

class CompteRoute extends sfObjectRoute implements InterfaceCompteRoute {

    protected $compte = null;

    protected function getObjectForParameters($parameters = null) {
      $this->compte = CompteClient::getInstance()->find(CompteClient::getInstance()->getId($parameters['identifiant']));

      $myUser = sfContext::getInstance()->getUser();
      if ($myUser->isAdmin()) {
          return $this->compte;
      }
      if ($myUser->hasTeledeclaration() && !$myUser->hasDrevAdmin()
            && $myUser->getCompte()->identifiant != $this->getCompte()->getSociete()->getMasterCompte()->identifiant)
      {
            throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page");
      }
      if($myUser->hasCredential(myUser::CREDENTIAL_HABILITATION)
            && $myUser->getCompte()->identifiant != $this->getCompte()->getSociete()->getMasterCompte()->identifiant
            && $this->getCompte()->getSociete()->type_societe != SocieteClient::TYPE_OPERATEUR)
      {
          throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page");
      }
      return $this->compte;
    }

    protected function doConvertObjectToArray($object) {
      return array("identifiant" => $object->getIdentifiant());
    }

    public function getSociete() {
      if (!$this->societe) {
           $this->societe = $this->getCompte()->getSociete();
      }
      return $this->societe;
    }

    public function getCompte() {
      if (!$this->compte) {
           $this->compte = $this->getObject();
      }
      return $this->compte;
    }
}
