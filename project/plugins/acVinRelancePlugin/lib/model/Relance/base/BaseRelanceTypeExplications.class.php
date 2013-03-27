<?php
/**
 * BaseRelanceTypeExplications
 * 
 * Base model for RelanceTypeExplications

 * @property string $origine_identifiant
 * @property string $origine_libelle
 * @property string $origine_date
 * @property string $alerte_identifiant
 * @property string $explications

 * @method string getOrigineIdentifiant()
 * @method string setOrigineIdentifiant()
 * @method string getOrigineLibelle()
 * @method string setOrigineLibelle()
 * @method string getOrigineDate()
 * @method string setOrigineDate()
 * @method string getAlerteIdentifiant()
 * @method string setAlerteIdentifiant()
 * @method string getExplications()
 * @method string setExplications()
 
 */

abstract class BaseRelanceTypeExplications extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Relance';
       $this->_tree_class_name = 'RelanceTypeExplications';
    }
                
}