<?php
/**
 * BaseRevendicationEtablissements
 * 
 * Base model for RevendicationEtablissements


 
 */

abstract class BaseRevendicationEtablissements extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Revendication';
       $this->_tree_class_name = 'RevendicationEtablissements';
    }
                
}