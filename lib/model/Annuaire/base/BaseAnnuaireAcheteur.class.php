<?php
/**
 * BaseAnnuaireAcheteur
 * 
 * Base model for AnnuaireAcheteur


 
 */

abstract class BaseAnnuaireAcheteur extends _AnnuaireTiers {
                
    public function configureTree() {
       $this->_root_class_name = 'Annuaire';
       $this->_tree_class_name = 'AnnuaireAcheteur';
    }
                
}