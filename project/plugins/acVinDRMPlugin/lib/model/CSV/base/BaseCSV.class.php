<?php
/**
 * BaseCSV
 * 
 * Base model for CSV
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property acCouchdbJson $_attachments
 * @property string $identifiant
 * @property string $periode
 * @property string $statut
 * @property acCouchdbJson $erreurs

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method acCouchdbJson get_attachments()
 * @method acCouchdbJson set_attachments()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getPeriode()
 * @method string setPeriode()
 * @method string getStatut()
 * @method string setStatut()
 * @method acCouchdbJson getErreurs()
 * @method acCouchdbJson setErreurs()
 
 */
 
abstract class BaseCSV extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'CSV';
    }
    
}