<?php
/**
 * Model for DRMDetailExport
 *
 */

class DRMDetailExport extends BaseDRMDetailExport {

    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }
}