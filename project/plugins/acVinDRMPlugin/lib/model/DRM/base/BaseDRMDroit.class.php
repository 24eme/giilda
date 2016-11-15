<?php
/**
 * BaseDRMDroit
 *
 * Base model for DRMDroit

 * @property float $volume_taxe
 * @property float $volume_reintegre
 * @property float $taux
 * @property string $code
 * @property string $libelle
 * @property float $total
 * @property float $report
 * @property float $cumul

 * @method float getVolumeTaxe()
 * @method float setVolumeTaxe()
 * @method float getVolumeReintegre()
 * @method float setVolumeReintegre()
 * @method float getTaux()
 * @method float setTaux()
 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method float getTotal()
 * @method float setTotal()
 * @method float getReport()
 * @method float setReport()
 * @method float getCumul()
 * @method float setCumul()

 */

abstract class BaseDRMDroit extends acCouchdbDocumentTree {

    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDroit';
    }

}
