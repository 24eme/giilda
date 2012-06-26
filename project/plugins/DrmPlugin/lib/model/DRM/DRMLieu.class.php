<?php
/**
 * Model for DRMLieu
 *
 */

class DRMLieu extends BaseDRMLieu {

	/**
     *
     * @return DRMLieu
     */
    public function getMention() {

        return $this->getParentNode();
    }
    public function getAppellation() {

        return $this->getMention()->getAppellation();
    }

    public function getCertification() {
        
        return $this->getAppellation()->getCertification();
    }
	
    public function getChildrenNode() {

        return $this->couleurs;
    }

    public function getLieux() {

        return null;
    }

}