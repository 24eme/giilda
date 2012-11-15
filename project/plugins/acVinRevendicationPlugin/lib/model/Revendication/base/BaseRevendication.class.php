<?php
/**
 * BaseRevendication
 * 
 * Base model for Revendication
 *
 * @property string $_id
 * @property string $_rev
 * @property acCouchdbJson $_attachments
 * @property string $type
 * @property string $odg
 * @property string $campagne
 * @property string $date_creation
 * @property string $etape
 * @property acCouchdbJson $datas
 * @property acCouchdbJson $erreurs

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method acCouchdbJson get_attachments()
 * @method acCouchdbJson set_attachments()
 * @method string getType()
 * @method string setType()
 * @method string getOdg()
 * @method string setOdg()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getEtape()
 * @method string setEtape()
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