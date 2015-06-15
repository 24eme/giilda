<?php
/**
 * BaseDRMNonAppurement
 * 
 * Base model for DRMNonAppurement


 
 */

abstract class BaseDRMNonAppurement extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMNonAppurement';
    }
                
}