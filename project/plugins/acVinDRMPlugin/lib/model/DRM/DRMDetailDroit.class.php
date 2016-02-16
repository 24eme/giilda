<?php
/**
 * Model for DRMDetailDroit
 *
 */

class DRMDetailDroit extends BaseDRMDetailDroit {
    protected function getConfig($interpro = 'INTERPRO-declaration') {
        return $this->getParent()->getCepage()->getConfig()->getDroitByType($this->getDocument()->getDate(), $interpro, $this->getKey());
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
