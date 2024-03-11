<?php

abstract class CompteGenerique extends acCouchdbDocument {


    public function getAdresse() {
        if($this instanceof Societe){
            return $this->siege->adresse;
        }
        return $this->_get('adresse');
    }

    public function setAdresse($s) {
        if($this instanceof Societe){
            return $this->siege->_set('adresse',$s);
        }
        return $this->_set('adresse',$s);
    }

    public function getCommune() {
        if($this instanceof Societe){
            return $this->siege->commune;
        }
        return $this->_get('commune');
    }

    public function setCommune($s) {
        if($this instanceof Societe){
            return $this->siege->_set('commune',$s);
        }
        return $this->_set('commune',$s);
    }

    public function getCodePostal() {
        if($this instanceof Societe){
            return $this->siege->code_postal;
        }
        return $this->_get('code_postal');
    }

    public function setCodePostal($s) {
        if($this instanceof Societe){
            return $this->siege->_set('code_postal',$s);
        }
        return $this->_set('code_postal',$s);
    }

    public function getPays() {
        if($this instanceof Societe){
            return $this->siege->pays;
        }
        return $this->_get('pays');
    }

    public function getPaysISO() {
        return strtoupper(substr($this->getPays(), 0, 2));
    }

    public function setPays($s) {
        if($this instanceof Societe){
            return $this->siege->_set('pays',$s);
        }
        return $this->_set('pays',$s);
    }

    public function getInsee() {
        if($this instanceof Societe){
            return $this->siege->insee;
        }
        return $this->_get('insee');
    }

    public function setInsee($s) {
        if($this instanceof Societe){
            return $this->siege->_set('insee',$s);
        }
        return $this->_set('insee',$s);
    }

    public function getAdresseComplementaire() {
        if($this instanceof Societe){
            return $this->siege->adresse_complementaire;
        }
        return $this->_get('adresse_complementaire');
    }

    public function setAdresseComplementaire($s) {
        if($this instanceof Societe){
            return $this->siege->_set('adresse_complementaire',$s);
        }
        return $this->_set('adresse_complementaire',$s);
    }

    public function setEmail($email) {
        return $this->_set('email', $email);
    }

    public function setTelephonePerso($s) {
        return $this->_set('telephone_perso', $s);
    }

    public function setTelephoneMobile($s) {
        return $this->_set('telephone_mobile', $s);
    }

    public function setTelephoneBureau($s) {

        return $this->_set('telephone_bureau', $s);
    }

    public function setSiteInternet($s) {
        return $this->_set('site_internet', $s);
    }

    public function setFax($fax) {

        return $this->_set('fax', $fax);
    }

    public function getEmail() {

        return $this->_get('email');
    }

    public function getTelephone() {
        if(!$this->exist('telephone')){
            return null;
        }
        return $this->_get('telephone');
    }

    public function setTelephone($phone) {
        $set = $this->_set('telephone_bureau', $phone);
        $this->remove('telephone');
        return $set;
    }

    public function getTelephoneBureau() {

        return $this->_get('telephone_bureau');
    }


    public function getTelephonePerso() {
        return $this->_get('telephone_perso');
    }

    public function getTelephoneMobile() {
        return $this->_get('telephone_mobile');
    }

    public function getSiteInternet() {
        return $this->_get('site_internet');
    }

    public function getFax() {

        return $this->_get('fax');
    }

    public static function extractIntitule($raisonSociale) {
        $intitules = "EARL|EI|ETS|EURL|GAEC|GFA|HOIRIE|IND|M|MM|Mme|MME|MR|SA|SARL|SAS|SASU|SC|SCA|SCE|SCEA|SCEV|SCI|SCV|SFF|SICA|SNC|SPH|STE|STEF";
        $intitule = null;

        if(preg_match("/^(".$intitules.") /", $raisonSociale, $matches)) {
            $intitule = $matches[1];
            $raisonSociale = preg_replace("/^".$intitule." /", "", $raisonSociale);
        }

        if(preg_match("/ \((".$intitules.")\)$/", $raisonSociale, $matches)) {
            $intitule = $matches[1];
            $raisonSociale = preg_replace("/ \((".$intitule.")\)$/", "", $raisonSociale);
        }

        return array($intitule, $raisonSociale);
    }

    public function getIntitule() {
        $extract = $this->extractIntitule($this->raison_sociale);

        return $extract[0];
    }

    public function getRaisonSocialeWithoutIntitule() {
        $extract = $this->extractIntitule($this->raison_sociale);

        return $extract[1];
    }

    public static function isSameAdresseComptes(InterfaceCompteGenerique $compte1, InterfaceCompteGenerique $compte2) {
        if
        (
            ($compte1->getAdresse() == $compte2->getAdresse() || !$compte1->getAdresse()) &&
            ($compte1->getCommune() == $compte2->getCommune() || !$compte1->getCommune()) &&
            ($compte1->getCodePostal() == $compte2->getCodePostal() || !$compte1->getCodePostal()) &&
            ($compte1->getInsee() == $compte2->getInsee() || !$compte1->getInsee()) &&
            ($compte1->getAdresseComplementaire() == $compte2->getAdresseComplementaire() || !$compte1->getAdresseComplementaire()) &&
            ($compte1->getPays() == $compte2->getPays() || !$compte1->getPays())
        )
        {
            return true;
        }
        return false;
    }

    public static function isSameContactComptes(InterfaceCompteGenerique $compte1, InterfaceCompteGenerique $compte2) {
        if
        (
            ($compte1->getTelephoneBureau() == $compte2->getTelephoneBureau() || !$compte1->getTelephoneBureau()) &&
            ($compte1->getTelephoneMobile() == $compte2->getTelephoneMobile() || !$compte1->getTelephoneMobile()) &&
            ($compte1->getTelephonePerso() == $compte2->getTelephonePerso() || !$compte1->getTelephonePerso()) &&
            ($compte1->getEmail() == $compte2->getEmail() || !$compte1->getEmail()) &&
            ($compte1->getFax() == $compte2->getFax() || !$compte1->getFax()) &&
            ($compte1->getSiteInternet() == $compte2->getSiteInternet() || !$compte1->getSiteInternet())
        )
        {
            return true;
        }
        return false;
    }

    public function isSameAdresseThan(InterfaceCompteGenerique $compte) {

        return self::isSameAdresseComptes($this, $compte);
    }

    public function isSameContactThan(InterfaceCompteGenerique $compte) {

        return self::isSameContactComptes($this, $compte);
    }

    public function pushContactAndAdresseTo(InterfaceCompteGenerique $compte) {
        $this->pushAdresseTo($compte);
        $this->pushContactTo($compte);
    }

    public function pushAdresseTo(InterfaceCompteGenerique $compte) {
        $ret = false;
        if ($compte->adresse != $this->getAdresse()) {
          $compte->adresse = $this->getAdresse();
          $ret = true;
        }
        if ($compte->adresse_complementaire != $this->getAdresseComplementaire()) {
          $compte->adresse_complementaire = $this->getAdresseComplementaire();
          $ret = true;
        }
        if ($compte->commune != $this->getCommune()) {
          $compte->commune = $this->getCommune();
          $ret = true;
        }
        if ($compte->code_postal != $this->getCodePostal()) {
          $compte->code_postal = $this->getCodePostal();
          $ret = true;
        }
        if ($compte->insee != $this->getInsee()) {
          $compte->insee = $this->getInsee();
          $ret = true;
        }
        if ($compte->pays != $this->getPays()) {
          $compte->pays = $this->getPays();
          $ret = true;
        }
        return $ret;
    }

    public function pushContactTo(InterfaceCompteGenerique $compte) {
        $ret = false;
        if ($compte->telephone_bureau != $this->getTelephoneBureau()) {
          $compte->telephone_bureau = $this->getTelephoneBureau();
          $ret = true;
        }
        if ($compte->email != $this->getEmail()) {
          $compte->email = $this->getEmail();
          $ret = true;
        }
        if ($compte->fax != $this->getFax()) {
          $compte->fax = $this->getFax();
          $ret = true;
        }
        if ($compte->telephone_perso != $this->getTelephonePerso()) {
          $compte->telephone_perso = $this->getTelephonePerso();
          $ret = true;
        }
        if ($compte->telephone_mobile != $this->getTelephoneMobile()) {
          $compte->telephone_mobile = $this->getTelephoneMobile();
          $ret = true;
        }
        if ($compte->site_internet != $this->getSiteInternet()) {
          $compte->site_internet = $this->getSiteInternet();
          $ret = true;
        }
        return $ret;
    }

    public function pullContactAndAdresseFrom(InterfaceCompteGenerique $compte) {
        $this->pullAdresseFrom($compte);
        $this->pullContactFrom($compte);
    }

    public static function pullAdresse(InterfaceCompteGenerique $compteTo, InterfaceCompteGenerique $compteFrom) {
        $compteTo->setAdresse($compteFrom->adresse);
        $compteTo->setAdresseComplementaire($compteFrom->adresse_complementaire);
        $compteTo->setCommune($compteFrom->commune);
        $compteTo->setCodePostal($compteFrom->code_postal);
        $compteTo->setInsee($compteFrom->insee);
        $compteTo->setPays($compteFrom->pays);
    }

    public function pullAdresseFrom(InterfaceCompteGenerique $compteFrom) {
        self::pullAdresse($this, $compteFrom);
    }

    public static function pullContact(InterfaceCompteGenerique $compteTo, InterfaceCompteGenerique $compteFrom) {
        $compteTo->setTelephoneBureau($compteFrom->telephone_bureau);
        $compteTo->setEmail($compteFrom->email);
        $compteTo->setFax($compteFrom->fax);
        $compteTo->setTelephonePerso($compteFrom->telephone_perso);
        $compteTo->setTelephoneMobile($compteFrom->telephone_mobile);
        $compteTo->setSiteInternet($compteFrom->site_internet);
    }

    public function pullContactFrom(InterfaceCompteGenerique $compteFrom) {
        self::pullContact($this, $compteFrom);
    }

}
