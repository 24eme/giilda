<?php
/**
 * BaseDRMCrdsRegime
 * 
 * Base model for DRMCrdsRegime


 
 */

abstract class BaseDRMCrdsRegime extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCrdsRegime';
    }
                
}