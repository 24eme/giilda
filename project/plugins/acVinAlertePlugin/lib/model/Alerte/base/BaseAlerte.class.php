<?php
/**
 * BaseAlerte
 * 
 * Base model for Alerte
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $type_alerte
 * @property string $date_creation
 * @property string $id_document
 * @property string $declarant_nom
 * @property string $identifiant
 * @property acCouchdbJson $statuts

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getTypeAlerte()
 * @method string setTypeAlerte()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getIdDocument()
 * @method string setIdDocument()
 * @method string getDeclarantNom()
 * @method string setDeclarantNom()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method acCouchdbJson getStatuts()
 * @method acCouchdbJson setStatuts()
 
 */
 
abstract class BaseAlerte extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Alerte';
    }
    
}