<?php

class myUser extends sfBasicSecurityUser
{
    const SESSION_COMPTE_LOGIN = "COMPTE_LOGIN";
    const SESSION_COMPTE_DOC_ID = "COMPTE_DOC_ID";
    const NAMESPACE_COMPTE = "COMPTE";

    public function signIn($login) 
    {
        $this->setAuthenticated(true);
        $this->setAttribute(self::SESSION_COMPTE_LOGIN, $login, self::NAMESPACE_COMPTE);

        $compte = CompteClient::getInstance()->findByLogin($login);
        
        if(!$compte) {
            $this->setAttribute(self::SESSION_COMPTE_DOC_ID, $compte->_id, self::NAMESPACE_COMPTE);
            foreach($compte->droits as $droit) {
                $roles = Roles::getRoles($droit);
                $this->addCredentials($roles);
            }
        }
        
    }

    public function signOut() 
    {
        $this->setAuthenticated(false);
        $this->clearCredentials();
        $this->getAttributeHolder()->removeNamespace(self::NAMESPACE_COMPTE);
    }

    public function getCompte() 
    {
        return CompteClient::getInstance()->find($this->getAttribute(self::SESSION_COMPTE_DOC_ID, null, self::NAMESPACE_COMPTE));
    }



}