<?php
/**
 * BaseStockProduit
 * 
 * Base model for StockProduit

 * @property string $produit_type
 * @property string $produit_libelle
 * @property string $produit_hash
 * @property float $stock_initial
 * @property float $stock_revendique
 * @property float $vente_vin
 * @property float $stock_theorique
 * @property float $stock_consome

 * @method string getProduitType()
 * @method string setProduitType()
 * @method string getProduitLibelle()
 * @method string setProduitLibelle()
 * @method string getProduitHash()
 * @method string setProduitHash()
 * @method float getStockInitial()
 * @method float setStockInitial()
 * @method float getStockRevendique()
 * @method float setStockRevendique()
 * @method float getVenteVin()
 * @method float setVenteVin()
 * @method float getStockTheorique()
 * @method float setStockTheorique()
 * @method float getStockConsome()
 * @method float setStockConsome()
 
 */

abstract class BaseStockProduit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Stock';
       $this->_tree_class_name = 'StockProduit';
    }
                
}