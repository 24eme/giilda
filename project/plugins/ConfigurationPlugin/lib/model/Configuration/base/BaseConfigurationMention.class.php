<?php
/**
 * BaseConfigurationMention
 * 
 * Base model for ConfigurationMention

 * @property string $libelle
 * @property string $code
 * @property acCouchdbJson $departements
 * @property ConfigurationDetail $detail

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCode()
 * @method string setCode()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method ConfigurationDetail getDetail()
 * @method ConfigurationDetail setDetail()
 
 */

abstract class BaseConfigurationMention extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationMention';
    }
                
}