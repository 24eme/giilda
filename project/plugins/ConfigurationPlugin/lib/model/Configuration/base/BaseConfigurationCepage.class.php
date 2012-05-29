<?php
/**
 * BaseConfigurationCepage
 * 
 * Base model for ConfigurationCepage

 * @property string $libelle
 * @property string $code

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCode()
 * @method string setCode()
 
 */

abstract class BaseConfigurationCepage extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCepage';
    }
                
}