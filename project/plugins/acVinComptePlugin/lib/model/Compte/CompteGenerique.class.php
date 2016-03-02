<?php

abstract class CompteGenerique extends acCouchdbDocument {

    protected $adresse_complementaire = null;
    protected $telephone_mobile = null;
    protected $telephone_perso = null;
    protected $site_internet = null;

    public function getAdresse() {

        return $this->siege->adresse;
    }

    public function setAdresse($s) {

        return ($this->siege->adresse = $s);
    }

    public function getCommune() {

        return $this->siege->commune;
    }

    public function setCommune($s) {
        return ($this->siege->commune = $s);
    }

    public function getCodePostal() {

        return $this->siege->code_postal;
    }

    public function setCodePostal($s) {
       
        return ($this->siege->code_postal= $s);
    }

    public function getPays() {
        
        return $this->siege->pays;
    }

    public function setPays($s) {
       
        return ($this->siege->pays = $s);
    }

    public function getAdresseComplementaire() {

        return $this->siege->adresse_complementaire;
    }

    public function setAdresseComplementaire($s) {
        return ($this->siege->adresse_complementaire = $s);
    }

    public function setEmail($email) {
        
        return $this->_set('email', $email);
    }

    public function setTelephonePerso($s) {
        $this->telephone_perso = $s;
        return true;
    }
    
    public function setTelephoneMobile($s) {
        $this->telephone_mobile = $s;
        return true;
    }

    public function setTelephoneBureau($tel) {
        
        return $this->setTelephone($tel);
    }

    public function setSiteInternet($s) {
        $this->site_internet = $s;
        return true;
    }

    public function setFax($fax) {
        if ($fax)
            $this->_set('fax', $this->cleanPhone($fax));
    }

    public function getEmail() {

        return $this->_get('email');
    }

    public function getTelephone() {

        return $this->_get('telephone');
    }

    public function setTelephone($phone) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone));
    }

    public function getTelephoneBureau() {
        
        return $this->getTelephone();
    }

    public function getTelephonePerso() {
        if (!$this->telephone_perso) {
            $this->telephone_perso = $this->getMasterCompte()->telephone_perso;
        }
        return $this->telephone_perso;
    }

    public function getTelephoneMobile() {
        if (!$this->telephone_mobile) {
            $this->telephone_mobile = $this->getMasterCompte()->telephone_mobile;
        }
        return $this->telephone_mobile;
    }

    public function getSiteInternet() {
        if (!$this->site_internet) {
            $this->site_internet = $this->getMasterCompte()->site_internet;
        }
        return $this->site_internet;
    }

    public function getFax() {

        return $this->_get('fax');
    }

    protected function cleanPhone($phone) {

        return $phone;
    }

    public function pushToCompte($compte) {
        $compte->adresse = $this->getAdresse();
        $compte->adresse_complementaire = $this->getAdresseComplementaire();
        $compte->commune= $this->getCommune();
        $compte->code_postal = $this->getCodePostal();
        $compte->pays = $this->getPays();
        $compte->telephone_bureau= $this->getTelephoneBureau();
        $compte->email = $this->getEmail();
        $compte->fax = $this->getFax();
        $compte->telephone_perso = $this->getTelephonePerso();
        $compte->telephone_mobile = $this->getTelephoneMobile();
        $compte->site_internet = $this->getSiteInternet();
    }

}