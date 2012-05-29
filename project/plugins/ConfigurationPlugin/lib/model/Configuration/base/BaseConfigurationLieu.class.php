<?php
/**
 * BaseConfigurationLieu
 * 
 * Base model for ConfigurationLieu

 * @property string $libelle
 * @property string $code
 * @property acCouchdbJson $departements
 * @property ConfigurationDetail $detail
 * @property acCouchdbJson $couleurs

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCode()
 * @method string setCode()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method ConfigurationDetail getDetail()
 * @method ConfigurationDetail setDetail()
 * @method acCouchdbJson getCouleurs()
 * @method acCouchdbJson setCouleurs()
 
 */

abstract class BaseConfigurationLieu extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationLieu';
    }
                
}