<?php
/**
 * BaseDRMMouvementDetails
 * 
 * Base model for DRMMouvementDetails


 
 */

abstract class BaseDRMMouvementDetails extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMMouvementDetails';
    }
                
}