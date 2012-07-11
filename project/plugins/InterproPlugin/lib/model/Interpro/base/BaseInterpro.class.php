<?php
/**
 * BaseInterpro
 * 
 * Base model for Interpro
 *
 * @property string $_id
 * @property string $_rev
 * @property string $identifiant
 * @property string $nom
 * @property string $type
 * @property string $statut

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getNom()
 * @method string setNom()
 * @method string getType()
 * @method string setType()
 * @method string getStatut()
 * @method string setStatut()
 
 */
 
abstract class BaseInterpro extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Interpro';
    }
    
}