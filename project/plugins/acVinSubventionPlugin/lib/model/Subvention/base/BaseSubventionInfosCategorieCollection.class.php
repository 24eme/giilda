<?php
/**
 * BaseSubventionInfosCategorieCollection
 * 
 * Base model for SubventionInfosCategorieCollection


 
 */

abstract class BaseSubventionInfosCategorieCollection extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Subvention';
       $this->_tree_class_name = 'SubventionInfosCategorieCollection';
    }
                
}