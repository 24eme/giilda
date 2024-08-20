<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class sfCredentialActions
 * @author mathurin
 */
class sfCredentialActions extends sfActions {

    const CREDENTIAL_ADMIN = "admin";
    const CREDENTIAL_COMPTA = "compta";
    const CREDENTIAL_TRANSACTIONS = "transactions";
    const CREDENTIAL_PRESSE = "presse";
    const CREDENTIAL_DIRECTION = "direction";
    const CREDENTIAL_AUTRE = "autre";
    const CREDENTIAL_BUREAU = "bureau";

    protected function getUserCredential() {

        return self::CREDENTIAL_ADMIN;
    }

    protected function getSocieteTypesRights() {
        $this->user = $this->getUserCredential();
        if (!$this->user) {
            return;
        }

        return array();
    }


    protected function applyRights() {

        //reduction de droits dans le module contact
        $this->reduct_rights = false;

        //reduction des droits en lecture seule pour le module contact
        $this->modification = true;

        $this->user = $this->getUserCredential();
    }

}
