<?php

class myUser extends sfBasicSecurityUser
{
	const CREDENTIAL_ADMIN = 'admin';

	protected $tiers = null;

	public function getTiers() {		
		return $this->tiers;
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
