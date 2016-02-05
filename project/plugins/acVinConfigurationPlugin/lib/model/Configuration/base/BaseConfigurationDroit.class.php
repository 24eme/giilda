<?php
/**
 * BaseConfigurationDroit
 * 
 * Base model for ConfigurationDroit

 * @property string $date
 * @property string $taux
 * @property string $code
 * @property string $libelle

 * @method string getDate()
 * @method string setDate()
 * @method string getTaux()
 * @method string setTaux()
 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 
 */

abstract class BaseConfigurationDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDroit';
    }
                
}