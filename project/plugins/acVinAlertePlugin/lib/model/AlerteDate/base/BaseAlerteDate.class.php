<?php
/**
 * BaseAlerteDate
 * 
 * Base model for AlerteDate
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $date

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getDate()
 * @method string setDate()
 
 */
 
abstract class BaseAlerteDate extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'AlerteDate';
    }
    
}