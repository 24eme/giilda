<?php
/**
 * BaseRevendicationEtablissements
 * 
 * Base model for RevendicationEtablissements

 * @property string $declarant_cvi
 * @property string $declarant_nom
 * @property string $commune
 * @property acCouchdbJson $produits

 * @method string getDeclarantCvi()
 * @method string setDeclarantCvi()
 * @method string getDeclarantNom()
 * @method string setDeclarantNom()
 * @method string getCommune()
 * @method string setCommune()
 * @method acCouchdbJson getProduits()
 * @method acCouchdbJson setProduits()
 
 */

abstract class BaseRevendicationEtablissements extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Revendication';
       $this->_tree_class_name = 'RevendicationEtablissements';
    }
                
}