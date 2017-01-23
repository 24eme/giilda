<?php
/**
 * BaseDRMDetailNoeud
 * 
 * Base model for DRMDetailNoeud


 
 */

abstract class BaseDRMDetailNoeud extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetailNoeud';
    }
                
}