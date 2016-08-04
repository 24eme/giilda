<?php
/**
 * BaseAnnuaireRepresentant
 * 
 * Base model for AnnuaireRepresentant


 
 */

abstract class BaseAnnuaireRepresentant extends _AnnuaireTiers {
                
    public function configureTree() {
       $this->_root_class_name = 'Annuaire';
       $this->_tree_class_name = 'AnnuaireRepresentant';
    }
                
}