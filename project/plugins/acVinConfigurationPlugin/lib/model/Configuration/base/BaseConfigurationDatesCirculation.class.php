<?php
/**
 * BaseConfigurationDatesCirculation
 * 
 * Base model for ConfigurationDatesCirculation


 
 */

abstract class BaseConfigurationDatesCirculation extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDatesCirculation';
    }
                
}