<?php
/**
 * BaseDRMCouleur
 * 
 * Base model for DRMCouleur

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_entrees_revendique
 * @property float $total_recolte
 * @property float $total_sorties
 * @property float $total_sorties_revendique
 * @property float $total_facturable
 * @property float $total_revendique
 * @property float $total
 * @property acCouchdbJson $cepages

 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalEntreesRevendique()
 * @method float setTotalEntreesRevendique()
 * @method float getTotalRecolte()
 * @method float setTotalRecolte()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotalSortiesRevendique()
 * @method float setTotalSortiesRevendique()
 * @method float getTotalFacturable()
 * @method float setTotalFacturable()
 * @method float getTotalRevendique()
 * @method float setTotalRevendique()
 * @method float getTotal()
 * @method float setTotal()
 * @method acCouchdbJson getCepages()
 * @method acCouchdbJson setCepages()
 
 */

abstract class BaseDRMCouleur extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCouleur';
    }
                
}