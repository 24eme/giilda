<?php
/**
 * BaseDRMDetailNoeud
 * 
 * Base model for DRMDetailNoeud

 * @property float $bloque
 * @property float $warrante
 * @property float $instance
 * @property float $commercialisable

 * @method float getBloque()
 * @method float setBloque()
 * @method float getWarrante()
 * @method float setWarrante()
 * @method float getInstance()
 * @method float setInstance()
 * @method float getCommercialisable()
 * @method float setCommercialisable()
 
 */

abstract class BaseDRMDetailNoeud extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetailNoeud';
    }
                
}