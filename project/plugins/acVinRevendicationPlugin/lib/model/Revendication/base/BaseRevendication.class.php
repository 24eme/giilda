<?php
/**
 * BaseRevendication
 * 
 * Base model for Revendication
 *
 * @property string $_id
 * @property string $_rev
 * @property string $_attachments
 * @property string $type
 * @property integer $nb_data
 * @property string $odg
 * @property string $campagne
 * @property acCouchdbJson $datas
 * @property acCouchdbJson $erreurs

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string get_attachments()
 * @method string set_attachments()
 * @method string getType()
 * @method string setType()
 * @method integer getNbData()
 * @method integer setNbData()
 * @method string getOdg()
 * @method string setOdg()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method acCouchdbJson getDatas()
 * @method acCouchdbJson setDatas()
 * @method acCouchdbJson getErreurs()
 * @method acCouchdbJson setErreurs()
 
 */
 
abstract class BaseRevendication extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Revendication';
    }
    
}