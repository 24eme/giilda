<?php
/**
 * BaseAlerteGenerationSequences
 * 
 * Base model for AlerteGenerationSequences
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $type_alerte
 * @property acCouchdbJson $revisions

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getTypeAlerte()
 * @method string setTypeAlerte()
 * @method acCouchdbJson getRevisions()
 * @method acCouchdbJson setRevisions()
 
 */
 
abstract class BaseAlerteGenerationSequences extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'AlerteGenerationSequences';
    }
    
}