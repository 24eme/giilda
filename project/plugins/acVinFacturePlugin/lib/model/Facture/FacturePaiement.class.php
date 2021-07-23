<?php
/**
 * Model for FacturePaiement
 *
 */

class FacturePaiement extends BaseFacturePaiement {
    public function setVersementComptable($b){
        $this->_set('versement_comptable', $b);
        $this->getDocument()->updateVersementComptablePaiement();
    }

    public function setDate($d) {
        $ret = $this->_set('date', $d);
        $this->getDocument()->updateDatePaiementFromPaiements();
        return $ret;
    }

}