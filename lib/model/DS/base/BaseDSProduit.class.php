<?php
/**
 * BaseDSProduit
 * 
 * Base model for DSProduit

 * @property string $code_douane
 * @property string $produit_libelle
 * @property string $produit_hash
 * @property float $stock_initial
 * @property float $stock_revendique

 * @method string getCodeDouane()
 * @method string setCodeDouane()
 * @method string getProduitLibelle()
 * @method string setProduitLibelle()
 * @method string getProduitHash()
 * @method string setProduitHash()
 * @method float getStockInitial()
 * @method float setStockInitial()
 * @method float getStockRevendique()
 * @method float setStockRevendique()
 
 */

abstract class BaseDSProduit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DS';
       $this->_tree_class_name = 'DSProduit';
    }
                
}