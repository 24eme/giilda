<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acVinComptePlugin configuration.
 * 
 * @package    acVinComptePlugin
 * @subpackage plugin
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
abstract class TeledeclarationSecurityUser extends sfBasicSecurityUser 
{

    protected $_compte = null;
    const SESSION_COMPTE = 'compte';
    const NAMESPACE_COMPTE_AUTHENTICATED = "CompteSecurityUser_Authenticated";
    const NAMESPACE_COMPTE_USED = "CompteSecurityUser_Used";
    
    const CREDENTIAL_COMPTE = 'compte';
    const CREDENTIAL_ADMIN = 'admin';
    const CREDENTIAL_OPERATEUR = 'operateur';
    const CREDENTIAL_TELEDECLARATION = 'teledeclaration';
    
    const CREDENTIAL_OBSERVATOIRE_ECO = 'observatoire_eco';
    const CREDENTIAL_TELEDECLARATION_VRAC = 'teledeclaration_vrac';
    
    protected $_couchdb_type_namespace_compte= array("Compte" => self::NAMESPACE_COMPTE_AUTHENTICATED);
    
    protected $_namespace_credential_compte = array(self::NAMESPACE_COMPTE_AUTHENTICATED => self::CREDENTIAL_COMPTE,
                                                    self::NAMESPACE_COMPTE_USED => self::CREDENTIAL_COMPTE);    
    
    protected $_namespaces_compte = array(self::NAMESPACE_COMPTE_AUTHENTICATED,
                                          self::NAMESPACE_COMPTE_USED);

    protected $_credentials_compte = array(self::CREDENTIAL_COMPTE,
                                           self::CREDENTIAL_OPERATEUR,
                                           self::CREDENTIAL_ADMIN);

    /**
     *
     * @param sfEventDispatcher $dispatcher
     * @param sfStorage $storage
     * @param type $options 
     */
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array()) 
    {
        parent::initialize($dispatcher, $storage, $options);
        if (!$this->isAuthenticated()) {
            $this->signOut();
        }
    }

    /**
     *
     * @param string $cas_user 
     */
    public function signIn($cas_user) 
    {
        $compte = CompteClient::getInstance()->findByIdentifiant($cas_user);
        if (!$compte) {
            throw new sfException('compte does not exist');
        }
        $this->addCredential(self::CREDENTIAL_COMPTE);
        $this->signInCompte($compte);
        $this->setAuthenticated(true);
    }
    
    /**
     *
     * @param _Compte $compte 
     */
    public function signInFirst($compte) 
    {
        $this->addCredential(self::CREDENTIAL_COMPTE);
        $this->signInCompte($compte);
        $this->setAuthenticated(true);
    }

    /**
     * 
     */
    public function signOut() 
    {
        foreach($this->_namespaces_compte as $namespace) {
            $this->signOutCompte($namespace);
        }
        $this->setAuthenticated(false);
        $this->clearCredentials();
        
        $this->_compte = null;
        $this->getAttributeHolder()->removeNamespace($namespace);
    }

    /**
     *
     * @param _Compte $compte 
     */
    public function signInCompte($compte) 
    {
        $namespace = $this->_couchdb_type_namespace_compte[$compte->type];
        $this->signOutCompte($namespace);
        $this->setAttribute(self::SESSION_COMPTE, $compte->identifiant, $namespace);
        $this->addCredential($this->_namespace_credential_compte[$namespace]);
        foreach ($compte->droits as $credential) { 
            switch ($credential) {
                case self::CREDENTIAL_OBSERVATOIRE_ECO:
                    $this->addCredential(self::CREDENTIAL_TELEDECLARATION);
                    $this->addCredential(self::CREDENTIAL_OBSERVATOIRE_ECO);
                    break;
                case self::CREDENTIAL_TELEDECLARATION_VRAC:
                    $this->addCredential(self::CREDENTIAL_TELEDECLARATION);
                    $this->addCredential(self::CREDENTIAL_TELEDECLARATION_VRAC);
                    break;
            }
        }
        if ($this->hasCredential(self::CREDENTIAL_ADMIN)) {
            $this->addCredential(self::CREDENTIAL_OPERATEUR);
        }
    }
    
    /**
     *
     * @param string $namespace 
     */
    public function signOutCompte($namespace) 
    {
        $this->_compte = null;
     //   var_dump($this->_namespace_credential_compte); exit;
        $this->removeCredential($this->_namespace_credential_compte[$namespace]);
        $this->getAttributeHolder()->removeNamespace($namespace);
    }

    /**
     *
     * @return _Compte $compte 
     */
    public function getCompte() 
    {
        $this->requireCompte();
        if (is_null($this->_compte)) {
            $this->_compte = CompteClient::getInstance()->retrieveByLogin($this->getAttribute(self::SESSION_COMPTE, null, $this->getNamespaceCompte()));
            if (!$this->_compte) {
                $this->signOut();
            }
        }
        return $this->_compte;
    }
    
    protected function getNamespaceCompte() 
    {        
        if($this->hasCredential(self::CREDENTIAL_TELEDECLARATION)) {
            return self::NAMESPACE_COMPTE_AUTHENTICATED;
        } 
        return self::NAMESPACE_COMPTE_USED;       
    }

    /**
     * 
     */
    protected function requireCompte() 
    {
        if (!$this->isAuthenticated() && $this->hasCredential(self::CREDENTIAL_COMPTE)) {
	  		throw new sfException("you must be logged with a tiers");
        }
    }

}