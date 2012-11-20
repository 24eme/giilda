<?php
/**
 * BaseRevendicationErreurs
 * 
 * Base model for RevendicationErreurs


 
 */

abstract class BaseRevendicationErreurs extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Revendication';
       $this->_tree_class_name = 'RevendicationErreurs';
    }
                
}