<?php
/**
 * Model for Vrac
 *
 */

class Vrac extends BaseVrac {
    public function constructId() {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);
    }
}