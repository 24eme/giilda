<?php
/**
 * Model for DRMCrd
 *
 */

class DRMCrd extends BaseDRMCrd {
    
    public function getLibelle(){
        return DRMClient::$drm_crds_couleurs[$this->couleur].' - '.$this->centilitrage;
    }
}