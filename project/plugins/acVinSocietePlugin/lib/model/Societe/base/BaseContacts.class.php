<?php
/**
 * BaseContacts
 * 
 * Base model for Contacts

 * @property string $id_compte
 * @property string $ordre

 * @method string getIdCompte()
 * @method string setIdCompte()
 * @method string getOrdre()
 * @method string setOrdre()
 
 */

abstract class BaseContacts extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Societe';
       $this->_tree_class_name = 'Contacts';
    }
                
}