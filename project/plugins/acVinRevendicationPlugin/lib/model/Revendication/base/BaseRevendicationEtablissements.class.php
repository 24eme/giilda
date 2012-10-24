<?php
/**
 * BaseRevendicationEtablissements
 * 
 * Base model for RevendicationEtablissements

 * @property string $declarant_identifiant
 * @property string $declarant_nom
 * @property acCouchdbJson $produits

 * @method string getDeclarantIdentifiant()
 * @method string setDeclarantIdentifiant()
 * @method string getDeclarantNom()
 * @method string setDeclarantNom()
 * @method acCouchdbJson getProduits()
 * @method acCouchdbJson setProduits()
 
 */

abstract class BaseRevendicationEtablissements extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Revendication';
       $this->_tree_class_name = 'RevendicationEtablissements';
    }
                
}