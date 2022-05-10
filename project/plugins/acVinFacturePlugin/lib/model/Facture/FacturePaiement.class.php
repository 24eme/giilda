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

    public function getNumeroRemise() {
        $codeRemise = ($this->type_reglement && isset(FactureClient::$codesRemises[$this->type_reglement]))? FactureClient::$codesRemises[$this->type_reglement] : null;
        $date = str_replace('-', '', $this->date);
        return ($codeRemise && $date)? $date.$codeRemise : null;
    }

}
