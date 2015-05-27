<?php
/**
 * BaseDRMCrd
 * 
 * Base model for DRMCrd

 * @property string $stock_debut
 * @property string $couleur
 * @property string $centilitrage
 * @property string $entrees
 * @property string $sorties
 * @property string $pertes
 * @property string $stock_fin
 * @property string $detail_libelle
 * @property string $type_crd

 * @method string getStockDebut()
 * @method string setStockDebut()
 * @method string getCouleur()
 * @method string setCouleur()
 * @method string getCentilitrage()
 * @method string setCentilitrage()
 * @method string getEntrees()
 * @method string setEntrees()
 * @method string getSorties()
 * @method string setSorties()
 * @method string getPertes()
 * @method string setPertes()
 * @method string getStockFin()
 * @method string setStockFin()
 * @method string getDetailLibelle()
 * @method string setDetailLibelle()
 * @method string getTypeCrd()
 * @method string setTypeCrd()
 
 */

abstract class BaseDRMCrd extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCrd';
    }
                
}