<?php
/**
 * BaseFacturePaiements
 * 
 * Base model for FacturePaiements


 
 */

abstract class BaseFacturePaiements extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Facture';
       $this->_tree_class_name = 'FacturePaiements';
    }
                
}