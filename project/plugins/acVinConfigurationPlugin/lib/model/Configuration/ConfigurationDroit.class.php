<?php

/**
 * Model for ConfigurationDroit
 *
 */
class ConfigurationDroit extends BaseConfigurationDroit {

    public function getNoeud() {

        return $this->getParent()->getNoeud();
    }

    public function getTaux() {
        $taux = $this->_get('taux');
        if (!is_string($taux)) {
            return $taux;
        }
        $matched = array();
        preg_match('/^([-+]{1})([0-9.]+)$/', $taux, $matched);
        if (count($matched)) {
            $sign = $matched[1];
            $addTaux = $matched[2];
            return array($sign,$addTaux);
        } 
        return $taux;
    }
    
    public function getStringTaux() {
        $taux = $this->getTaux();
        if(is_array($taux)){
            return $taux[0].$taux[1];
        }
        return $taux;
    }

}
