<?php
/**
 * BaseRevendicationErreurs
 * 
 * Base model for RevendicationErreurs

 * @property string $type_erreur
 * @property string $data_erreur
 * @property string $libelle_erreur
 * @property string $ligne
 * @property string $num_ligne

 * @method string getTypeErreur()
 * @method string setTypeErreur()
 * @method string getDataErreur()
 * @method string setDataErreur()
 * @method string getLibelleErreur()
 * @method string setLibelleErreur()
 * @method string getLigne()
 * @method string setLigne()
 * @method string getNumLigne()
 * @method string setNumLigne()
 
 */

abstract class BaseRevendicationErreurs extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Revendication';
       $this->_tree_class_name = 'RevendicationErreurs';
    }
                
}