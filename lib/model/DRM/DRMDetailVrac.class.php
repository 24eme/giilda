<?php
/**
 * Model for DRMDetailVrac
 *
 */

class DRMDetailVrac extends BaseDRMDetailVrac {
    
    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }
}