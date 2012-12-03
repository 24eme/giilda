<?php
/**
 * BaseChai
 * 
 * Base model for Chai

 * @property string $id_etablissement
 * @property string $ordre
 * @property string $adresse_societe

 * @method string getIdEtablissement()
 * @method string setIdEtablissement()
 * @method string getOrdre()
 * @method string setOrdre()
 * @method string getAdresseSociete()
 * @method string setAdresseSociete()
 
 */

abstract class BaseChai extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Societe';
       $this->_tree_class_name = 'Chai';
    }
                
}