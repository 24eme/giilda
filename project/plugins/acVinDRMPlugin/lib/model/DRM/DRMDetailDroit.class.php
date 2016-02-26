<?php
/**
 * Model for DRMDetailDroit
 *
 */

class DRMDetailDroit extends BaseDRMDetailDroit {
    protected function getConfig($interpro = 'INTERPRO-declaration') {
        return $this->getParent()->getCepage()->getConfig()->getDroitByType($this->getDocument()->getDate(), $this->getKey(), $interpro);
    }

    protected function init($params = array()) {
        parent::init($params);

        $this->taux = null;
    }

    public function calcul() {
        if (is_null($this->taux)) {
                $this->taux = floatval($this->getConfig()->taux);
            }
        }
    }
