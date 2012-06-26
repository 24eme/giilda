<?php
/**
 * BaseDRMDetail
 * 
 * Base model for DRMDetail

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property acCouchdbJson $label
 * @property string $label_supplementaire
 * @property acCouchdbJson $vrac
 * @property acCouchdbJson $millesimes
 * @property DRMDetailNoeud $stocks_debut
 * @property DRMDetailNoeud $entrees
 * @property DRMDetailNoeud $sorties
 * @property DRMDetailNoeud $stocks_fin

 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method acCouchdbJson getLabel()
 * @method acCouchdbJson setLabel()
 * @method string getLabelSupplementaire()
 * @method string setLabelSupplementaire()
 * @method acCouchdbJson getVrac()
 * @method acCouchdbJson setVrac()
 * @method acCouchdbJson getMillesimes()
 * @method acCouchdbJson setMillesimes()
 * @method DRMDetailNoeud getStocksDebut()
 * @method DRMDetailNoeud setStocksDebut()
 * @method DRMDetailNoeud getEntrees()
 * @method DRMDetailNoeud setEntrees()
 * @method DRMDetailNoeud getSorties()
 * @method DRMDetailNoeud setSorties()
 * @method DRMDetailNoeud getStocksFin()
 * @method DRMDetailNoeud setStocksFin()
 
 */

abstract class BaseDRMDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetail';
    }
                
}