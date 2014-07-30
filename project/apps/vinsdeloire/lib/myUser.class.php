<?php

class myUser extends sfBasicSecurityUser
{
    const SESSION_COMPTE_LOGIN = "COMPTE_LOGIN";
    const SESSION_COMPTE_DOC_ID = "COMPTE_DOC_ID";
    const NAMESPACE_COMPTE = "COMPTE";
    const NAMESPACE_COMPTE_ORIGIN = "COMPTE_ORIGIN";
    
    public function signIn($login_or_compte) 
    {
        if(is_object($login_or_compte) && $login_or_compte instanceof Compte) {
            $compte = $login_or_compte;
            $login = $compte->getLogin();
        } else {
            $compte = CompteClient::getInstance()->findByLogin($login_or_compte);
            $login = $login_or_compte;
        }
        $this->setAttribute(self::SESSION_COMPTE_LOGIN, $login, self::NAMESPACE_COMPTE);
        
        if($compte) {
            $this->setAttribute(self::SESSION_COMPTE_DOC_ID, $compte->_id, self::NAMESPACE_COMPTE);
            foreach($compte->droits as $droit) {
                $roles = Roles::getRoles($droit);
                $this->addCredentials($roles);
            }
        }
        
    }
    
    
    public function signInOrigin($login_or_compte) 
    {        
        if(is_object($login_or_compte) && $login_or_compte instanceof Compte) {
            $compte = $login_or_compte;
            $login = $compte->getLogin();
        } else {
            $compte = CompteClient::getInstance()->findByLogin($login_or_compte);
            $login = $login_or_compte;
        }
        $this->setAuthenticated(true);
        $this->setAttribute(self::SESSION_COMPTE_LOGIN, $login, self::NAMESPACE_COMPTE_ORIGIN);
         
        if($compte) {
            $this->setAttribute(self::SESSION_COMPTE_DOC_ID, $compte->_id, self::NAMESPACE_COMPTE_ORIGIN);
        }
        $this->signIn($login_or_compte);        
    }

    public function signOut() 
    {
        $this->clearCredentials();
        $this->getAttributeHolder()->removeNamespace(self::NAMESPACE_COMPTE);
    }
    
    public function signOutOrigin() 
    {
        $this->signOut();
        $this->setAuthenticated(false);
        $this->clearCredentials();
        $this->getAttributeHolder()->removeNamespace(self::NAMESPACE_COMPTE_ORIGIN);
    }

    public function getCompte() 
    {
        if(!$this->getAttribute(self::SESSION_COMPTE_DOC_ID, null, self::NAMESPACE_COMPTE)){
            return null;
        }
        return CompteClient::getInstance()->find($this->getAttribute(self::SESSION_COMPTE_DOC_ID, null, self::NAMESPACE_COMPTE));
    }

    public function hasTeledeclaration() {
        return $this->isAuthenticated() && $this->getCompte() && $this->hasCredential(Roles::TELEDECLARATION);
    }
    
    public function hasTeledeclarationVrac() {
        return $this->hasTeledeclaration() && $this->hasCredential(Roles::TELEDECLARATION_VRAC);
    }
}