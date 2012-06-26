<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {

	public function getLibelle() {

	  	return $this->getConfig()->getLibelle();
	}
	
	public function getCode() {
	  
	  	return $this->getConfig()->getCode();
	}

	public function getPreviousSisterWithMouvementCheck() {
		
		return null;
	}

	public function getNextSisterWithMouvementCheck() {

		return null;
	}

	public function getChildrenNode() {

		return $this->genres;
	}

}