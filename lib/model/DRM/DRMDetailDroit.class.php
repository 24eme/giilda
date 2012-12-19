<?php
/**
 * Model for DRMDetailDroit
 *
 */

class DRMDetailDroit extends BaseDRMDetailDroit {
    protected function getConfig($interpro = 'INTERPRO-inter-loire') {

        return $this->getParent()->getCepage()->getConfig()->getDroits($interpro)->get($this->getKey())->getCurrentDroit($this->getDocument()->getPeriode());
    }

    protected function update($params = array()) {
        $this->calcul();
    }

    public function calcul() {
        if (is_null($this->taux)) {
            $this->taux = $this->getConfig()->taux;
        }
    }
}