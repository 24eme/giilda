<?php

class VracSecurity implements SecurityInterface {



    protected $vrac;
    protected $myUser;
    protected $compte;
    
    const DROITS_TELEDECLARATION_VRAC = 'teledeclaration_vrac';
    const DROITS_TELEDECLARATION_VRAC_CREATION = 'teledeclaration_vrac_creation';

    public static function getInstance($myUser, $vrac = null) {

        return new VracSecurity($myUser, $vrac);
    }

    public function __construct($myUser, $vrac = null) {
        $this->myUser = $myUser;
        $this->vrac = $vrac;
        $this->compte = $this->myUser->getCompte();
    }

    public function isAuthorized($droits) {
        if($this->isAuthorizedCompte($this->compte, $droits)) {
            return true;
        }
        return false;
    }

    public function isAuthorizedCompte($compte, $droits) {
        if(!is_array($droits)) {
            $droits = array($droits);
        }
        
        if(!$compte->_id){
            return false;
        }
        
        if(!$compte->isTeledeclarantVrac()) {
            
            return false;
        }

        if(!$this->myUser->getCompte()->hasDroit(Roles::CONTRAT)) {

            return false;
        }

        if(in_array(self::DROITS_TELEDECLARATION_VRAC, $droits)) {

            return true;
        }

        /*** CREATION ***/

//        if(in_array(self::CREATION, $droits) && !$tiers->isDeclarantContratForResponsable()) {
//
//            return false;
//        }
//
//        if(in_array(self::CREATION, $droits)) {
//
//            return true;
//        }

        /*** EDITION ***/

//        if(!$this->vrac) {
//
//            return false;
//        }
//
//        if(!$this->vrac->isActeur($tiers->_id)) {
//
//            return false;
//        }
//
//        if(in_array(self::EDITION, $droits) && !$this->vrac->isProprietaire($tiers->_id)) {
//
//            return false;
//        }
//
//        if(in_array(self::EDITION, $droits) && !$this->vrac->isBrouillon()) {
//
//            return false;
//        }

        /*** SIGNATURE ***/

//        if(in_array(self::SIGNATURE, $droits) && $this->vrac->isValide()) {
//
//            return false;
//        }
//
//        if(in_array(self::SIGNATURE, $droits) && $this->vrac->hasSigne($tiers->_id)) {
//
//            return false;
//        }

        /*** SUPPRESSION ***/

//        if(in_array(self::SUPPRESSION, $droits) && !$this->vrac->isProprietaire($tiers->_id)) {
//
//            return false;
//        }
//
//        if(in_array(self::SUPPRESSION, $droits) && !$this->vrac->isSupprimable()) {
//
//            return false;
//        }

        /*** ENLEVEMENT ***/

//        if(in_array(self::ENLEVEMENT, $droits) && !$this->vrac->isProprietaire($tiers->_id)) {
//
//            return false;
//        }
//
//        if(in_array(self::ENLEVEMENT, $droits) && !$this->vrac->isValide()) {
//
//            return false;
//        }
//
//        if(in_array(self::ENLEVEMENT, $droits) && $this->vrac->isCloture()) {
//
//            return false;
//        }

        /*** CLOTURE ***/
//
//        if(in_array(self::CLOTURE, $droits) && !$this->vrac->isProprietaire($tiers->_id)) {
//
//            return false;
//        }
//
//        if(in_array(self::ENLEVEMENT, $droits) && !$this->vrac->isValide()) {
//
//            return false;
//        }
//
//        if(in_array(self::ENLEVEMENT, $droits) && $this->vrac->isCloture()) {
//
//            return false;
//        }
//
//        return true;
    }

}