<?php
/**
 * BaseConfigurationDateCirculation
 * 
 * Base model for ConfigurationDateCirculation

 * @property string $campagne
 * @property string $date_debut
 * @property string $date_fin

 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getDateDebut()
 * @method string setDateDebut()
 * @method string getDateFin()
 * @method string setDateFin()
 
 */

abstract class BaseConfigurationDateCirculation extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDateCirculation';
    }
                
}