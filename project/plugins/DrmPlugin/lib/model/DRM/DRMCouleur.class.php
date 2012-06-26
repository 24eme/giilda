<?php
/**
 * Model for DRMCouleur
 *
 */

class DRMCouleur extends BaseDRMCouleur {
    
    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {
        
        return $this->getParent()->getParent();
    }
    
    public function getChildrenNode() {

        return $this->cepages;
    }
	
}