<?php

class myUser extends TeledeclarationSecurityUser
{
    const CREDENTIAL_ADMIN = 'admin';

    protected $tiers = null;

    public function getTiers() {        
            return $this->tiers;
    }

  public function getCompte() {
    $user = parent::getCompte();  
      if($user){
          return $user;
      }
    $user = new stdClass();
    $user->_id = $this->getAttribute('AUTH_USER');
    $user->prenom = $user->_id;
    $user->nom = $user->_id;

    return $user;
  }

  /**
   * Récupération de l'interpro
   * @return Interpro
   */
  public function getInterpro()
  {
      $interpro = new Interpro();
      $interpro->identifiant = 'inter-loire';
      $interpro->nom = "Inter Loire";
      $interpro->set('_id', 'INTERPRO-'.$interpro->identifiant);

      return $interpro;
  }

}