<?php

class myUser extends AppUser {

  public function signIn($login_or_compte) {
      $compte = $this->registerCompteByNamespace($login_or_compte, self::NAMESPACE_COMPTE);
      if ($compte) {
          foreach ($compte->getDroits() as $droit) {
              $roles = Roles::getRoles($droit);
              $this->addCredentials($roles);
          }
      }
  }
}
