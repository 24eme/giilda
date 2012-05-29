<?php
/**
 * BaseConfigurationDetailLigne
 * 
 * Base model for ConfigurationDetailLigne

 * @property string $readable
 * @property string $writable

 * @method string getReadable()
 * @method string setReadable()
 * @method string getWritable()
 * @method string setWritable()
 
 */

abstract class BaseConfigurationDetailLigne extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDetailLigne';
    }
                
}