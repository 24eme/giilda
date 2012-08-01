<?php

abstract class TiersSecurityUser extends acVinCompteSecurityUser {

    protected $_tiers = null;
    const SESSION_TIERS = 'tiers';
    const NAMESPACE_TIERS = 'TiersSecurityUser';
    const CREDENTIAL_TIERS = 'tiers';
    const CREDENTIAL_INTERPRO = 'interpro';
    const CREDENTIAL_ETABLISSEMENT = 'etablissement';
    
    const CREDENTIAL_DROIT_DRM_PAPIER = 'drm_papier';
    const CREDENTIAL_DROIT_DRM_DTI = 'drm_dti';
    const CREDENTIAL_DROIT_VRAC = 'vrac';

    protected $_credentials_tiers = array(
        self::CREDENTIAL_TIERS,
        self::CREDENTIAL_INTERPRO,
        self::CREDENTIAL_ETABLISSEMENT
        );
    
    protected $_credentials_droits = array(
        self::CREDENTIAL_DROIT_DRM_PAPIER,
        self::CREDENTIAL_DROIT_DRM_DTI,
        self::CREDENTIAL_DROIT_VRAC
        );

    /**
     *
     * @param sfEventDispatcher $dispatcher
     * @param sfStorage $storage
     * @param type $options 
     */
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array()) {
        parent::initialize($dispatcher, $storage, $options);

        if (!$this->isAuthenticated()) {
            $this->signOutTiers();
        }
    }

    /**
     *
     * @param Etablissement $tiers 
     */
    public function signInTiers($tiers) {
        $this->requireCompte();
        $this->signOutTiers();
        $this->addCredential(self::CREDENTIAL_TIERS);
        if ($tiers->type == "Interpro") {
        	$this->addCredential(self::CREDENTIAL_INTERPRO);
        } elseif ($tiers->type == "Etablissement") {
        	$this->addCredential(self::CREDENTIAL_ETABLISSEMENT);
            foreach ($tiers->getDroits() as $credential) {
                $this->addCredential($credential);
            }
        }
        $this->setAttribute(self::SESSION_TIERS, $tiers->_id, self::NAMESPACE_TIERS);
    }

    /**
     * 
     */
    protected function clearCredentialsTiers() {
        foreach ($this->_credentials_tiers as $credential) {
            $this->removeCredential($credential);
        }
    }

    protected function clearCredentialsDroits() {
        foreach ($this->_credentials_droits as $credential) {
            $this->removeCredential($credential);
        }
    }

    /**
     * 
     */
    public function signOutTiers() {
        $this->_tiers = null;
        $this->getAttributeHolder()->removeNamespace(self::NAMESPACE_TIERS);
        $this->clearCredentialsTiers();
        $this->clearCredentialsDroits();
    }

    /**
     * @return Etablissement
     */
    public function getTiers($type = null) {
        $this->requireTiers();
        if (is_null($this->_tiers)) {
            $this->_tiers = array();
            if ($this->getAttribute(self::SESSION_TIERS, null, self::NAMESPACE_TIERS)) {
            	$this->_tiers = acCouchdbManager::getClient()->retrieveDocumentById($this->getAttribute(self::SESSION_TIERS, null, self::NAMESPACE_TIERS));
            } else {
                $this->_tiers = $this->getCompte()->getTiers();
            }
            if (!$this->_tiers) {
                $this->signOutCompte();
                throw new sfException("The tiers does not exist");
            }
        }
        return $this->_tiers;
    }

    public function getEtablissement($type = null) {
	return $this->getTiers($type);
    }
    /**
     * 
     */
    protected function requireTiers() {
        $this->requireCompte();
        if (!$this->hasCredential(self::CREDENTIAL_TIERS)) {
            throw new sfException("you must be logged in with a tiers");
        }
    }

    /**
     *
     * @param string $namespace 
     */
    public function signOutCompte($namespace) {
        $this->signOutTiers();
        parent::signOutCompte($namespace);
    }

}
