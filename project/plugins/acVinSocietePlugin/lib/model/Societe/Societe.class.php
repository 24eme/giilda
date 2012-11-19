<?php
/**
 * Model for Societe
 *
 */

class Societe extends BaseSociete {

    public function constructId() {
    $this->set_id('SOCIETE-'.$this->identifiant);
  }

}