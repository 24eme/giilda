<?php
/**
 * BaseConfigurationCouleur
 * 
 * Base model for ConfigurationCouleur

 * @property string $libelle
 * @property string $code
 * @property acCouchdbJson $cepages

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCode()
 * @method string setCode()
 * @method acCouchdbJson getCepages()
 * @method acCouchdbJson setCepages()
 
 */

abstract class BaseConfigurationCouleur extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCouleur';
    }
                
}