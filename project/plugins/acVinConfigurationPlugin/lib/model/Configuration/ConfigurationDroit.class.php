<?php
/**
 * Model for ConfigurationDroit
 *
 */

class ConfigurationDroit extends BaseConfigurationDroit {

    public function getNoeud() {

        return $this->getParent()->getNoeud();
    }

    public function setTaux($taux) {
        if(!is_null($taux) && $taux !== "")  {
            $taux = $taux * 1;
        }

        if($taux === "")  {
            $taux = null;
        }

        return $this->_set('taux' ,$taux);
    }

}
