<?php
/**
 * BaseEtablissementLiaisonsOperateurs
 * 
 * Base model for EtablissementLiaisonsOperateurs

 * @property string $id_societe
 * @property string $type_liaison

 * @method string getIdSociete()
 * @method string setIdSociete()
 * @method string getTypeLiaison()
 * @method string setTypeLiaison()
 
 */

abstract class BaseEtablissementLiaisonsOperateurs extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Etablissement';
       $this->_tree_class_name = 'EtablissementLiaisonsOperateurs';
    }
                
}