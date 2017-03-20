<?php
/**
 * BaseDRMESDetailCreationVrac
 *
 * Base model for DRMESDetailCreationVrac

 * @property string $identifiant
 * @property float $volume
 * @property float $prixhl
 * @property string $acheteur
 * @property string $date_enlevement
 * @property string $numero_document
 * @property string $type_document

 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method float getVolume()
 * @method float setVolume()
 * @method float getPrixhl()
 * @method float setPrixhl()
 * @method string getAcheteur()
 * @method string setAcheteur()
 * @method string getDateEnlevement()
 * @method string setDateEnlevement()
 * @method string getNumeroDocument()
 * @method string setNumeroDocument()
 * @method string getTypeDocument()
 * @method string setTypeDocument()
 */

abstract class BaseDRMESDetailCreationVrac extends acCouchdbDocumentTree {

    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMESDetailCreationVrac';
    }

}
