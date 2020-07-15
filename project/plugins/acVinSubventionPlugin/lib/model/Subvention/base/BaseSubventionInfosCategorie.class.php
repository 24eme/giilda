<?php
/**
 * BaseSubventionInfosCategorie
 * 
 * Base model for SubventionInfosCategorie

 * @property SubventionInfosCategorieCollection $gammes

 * @method SubventionInfosCategorieCollection getGammes()
 * @method SubventionInfosCategorieCollection setGammes()
 
 */

abstract class BaseSubventionInfosCategorie extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Subvention';
       $this->_tree_class_name = 'SubventionInfosCategorie';
    }
                
}