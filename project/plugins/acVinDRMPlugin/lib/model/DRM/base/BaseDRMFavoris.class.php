<?php
/**
 * BaseDRMFavoris
 * 
 * Base model for DRMFavoris


 
 */

abstract class BaseDRMFavoris extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMFavoris';
    }
                
}