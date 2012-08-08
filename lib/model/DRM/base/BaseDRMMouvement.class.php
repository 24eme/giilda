<?php
/**
 * BaseDRMMouvement
 * 
 * Base model for DRMMouvement

 * @property string $produit_hash
 * @property string $produit_libelle
 * @property string $type_hash
 * @property string $type_libelle
 * @property string $detail
 * @property string $facture
 * @property string $facturable

 * @method string getProduitHash()
 * @method string setProduitHash()
 * @method string getProduitLibelle()
 * @method string setProduitLibelle()
 * @method string getTypeHash()
 * @method string setTypeHash()
 * @method string getTypeLibelle()
 * @method string setTypeLibelle()
 * @method string getDetail()
 * @method string setDetail()
 * @method string getFacture()
 * @method string setFacture()
 * @method string getFacturable()
 * @method string setFacturable()
 
 */

abstract class BaseDRMMouvement extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMMouvement';
    }
                
}