<?php
/**
 * BaseDRMDetailCooperative
 * 
 * Base model for DRMDetailCooperative

 * @property string $cooperative_id
 * @property float $volume
 * @property string $date_enlevement

 * @method string getCooperativeId()
 * @method string setCooperativeId()
 * @method float getVolume()
 * @method float setVolume()
 * @method string getDateEnlevement()
 * @method string setDateEnlevement()
 
 */

abstract class BaseDRMDetailCooperative extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetailCooperative';
    }
                
}