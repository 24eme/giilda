<?php

abstract class CompteGenerique extends acCouchdbDocument {

    protected $adresse_complementaire = null;
    protected $telephone_mobile = null;
    protected $telephone_perso = null;
    protected $site_internet = null;

    public function setAdresse($s) {
        return ($this->siege->adresse = $s);
    }

    public function setCommune($s) {
        return ($this->siege->commune = $s);
    }

    public function setCodePostal($s) {
        return ($this->siege->code_postal= $s);
    }

    public function setPays($s) {
        return ($this->siege->pays = $s);
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

}