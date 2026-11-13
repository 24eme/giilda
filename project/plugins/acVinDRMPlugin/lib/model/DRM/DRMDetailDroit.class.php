<?php
/**
 * Model for DRMDetailDroit
 *
 */

class DRMDetailDroit extends BaseDRMDetailDroit {
    protected $config = null;

    protected function getConfig() {
        if(!is_null($this->config)) {

            return $this->config;
        }

        $this->config = $this->getParent()->getCepage()->getConfig()->getDroitByType($this->getDocument()->getDate(), $this->getKey());

        return $this->config;
    }

    protected function init($params = array()) {
        parent::init($params);

        $this->taux = null;
        $this->remove('interpro');
    }

    public function calcul() {
        if (is_null($this->taux)) {
                $this->taux = floatval($this->getConfig()->taux);
        }
        if (!$this->exist('interpro') && $this->getConfig()->exist('interpro')) {
            $this->add('interpro', $this->getConfig()->interpro);
        }
    }
}
