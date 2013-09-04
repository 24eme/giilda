<?php
/**
 * BaseAnnuaireVendeur
 * 
 * Base model for AnnuaireVendeur


 
 */

abstract class BaseAnnuaireVendeur extends _AnnuaireTiers {
                
    public function configureTree() {
       $this->_root_class_name = 'Annuaire';
       $this->_tree_class_name = 'AnnuaireVendeur';
    }
                
}