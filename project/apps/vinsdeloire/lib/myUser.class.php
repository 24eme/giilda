<?php

class myUser extends sfBasicSecurityUser
{
    const SESSION_COMPTE = "COMPTE";
    const NAMESPACE_COMPTE = "COMPTE";

    public function signIn(Compte $compte) 
    {
        $this->setAttribute(self::SESSION_COMPTE, $compte->_id, self::NAMESPACE_COMPTE);

        foreach($compte->droits as $droit) {
            $roles = Roles::getRoles($droit);
            $this->addCredentials($roles);
        }

        $this->setAuthenticated(true);
    }

    public function signOut() 
    {
        $this->setAuthenticated(false);
        $this->clearCredentials();
        $this->getAttributeHolder()->removeNamespace(self::NAMESPACE_COMPTE);
    }

    public function getCompte() 
    {
        return CompteClient::getInstance()->find($this->getAttribute(self::SESSION_COMPTE, null, self::NAMESPACE_COMPTE));
    }

    public function hasTeledeclaration() {
        return $this->isAuthenticated() && $this->getCompte() && $this->hasCredential(Roles::TELEDECLARATION);
    }
    
    public function hasTeledeclarationVrac() {
        return $this->hasTeledeclaration() && $this->hasCredential(Roles::TELEDECLARATION_VRAC);
    }
}