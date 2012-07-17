<?php
/**
 * Model for DRMDetailCooperative
 *
 */

class DRMDetailCooperative extends BaseDRMDetailCooperative {
	
	public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }
}