<?php
/**
 * BaseConfigurationDeclaration
 * 
 * Base model for ConfigurationDeclaration

 * @property acCouchdbJson $details
 * @property acCouchdbJson $certifications

 * @method acCouchdbJson getDetails()
 * @method acCouchdbJson setDetails()
 * @method acCouchdbJson getCertifications()
 * @method acCouchdbJson setCertifications()
 
 */

abstract class BaseConfigurationDeclaration extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDeclaration';
    }
                
}