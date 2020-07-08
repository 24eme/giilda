<?php

/**
 * Model for Subvention
 *
 */
class Subvention extends BaseSubvention  {

    public function __construct() {
        parent::__construct();
    }

    public function constructId() {
        $this->set('_id', 'SUBVENTION-'.$this->identifiant.'-'.$this->operation);
    }

    

}
