<?php
/**
 * Model for Contacts
 *
 */

class Contacts extends BaseContacts {

    public function createContact() {
        $compte = CompteClient::getInstance()->getNewId();
        
    }


}