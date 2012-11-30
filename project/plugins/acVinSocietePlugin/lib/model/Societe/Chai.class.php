<?php

/**
 * Model for Chai
 *
 */
class Chai extends BaseChai {

    public function initChai($id_etb) {
        $this->id_etablissement = $id_etb;
        $this->ordre = '0';
        $this->adresse_societe = '0';
    }

}