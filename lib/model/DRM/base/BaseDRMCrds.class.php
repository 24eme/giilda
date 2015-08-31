<?php
/**
 * BaseDRMCrds
 * 
 * Base model for DRMCrds


 
 */

abstract class BaseDRMCrds extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCrds';
    }
                
}