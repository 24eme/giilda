<?php

/**
 * Model for ConfigurationDroit
 *
 */
class ConfigurationDroit extends BaseConfigurationDroit {

    public function getNoeud() {

        return $this->getParent()->getNoeud();
    }

    public function isChapeau() {
        $taux = $this->_get('taux');
        if (!is_string($taux)) {
            return false;
        }
        return preg_match('/^[-+]/', $taux);
    }
    
    public function getTaux($printable = false) {
        if (!$this->isChapeau()) {
            return $this->_get('taux');
        }
        $masterTaux = $this->getMasterDroit()->getTaux(false);
        if ($printable) {
            return $masterTaux.' '.$this->_get('taux');
        }
        return $masterTaux + floatval($this->_get('taux'));
    }

    public function getMasterDroit() {
        return $this->getNoeud()->getParentNode()->getDroitByType($this->date, $this->code);
    }
    
    public function getStringTaux() {
        return $this->getTaux(true);
    }

}
