<?php
/**
 * BaseSV12
 * 
 * Base model for SV12
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $periode
 * @property string $negociant_identifiant
 * @property acCouchdbJson $negociant
 * @property acCouchdbJson $contrats
 * @property acCouchdbJson $valide

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getPeriode()
 * @method string setPeriode()
 * @method string getNegociantIdentifiant()
 * @method string setNegociantIdentifiant()
 * @method acCouchdbJson getNegociant()
 * @method acCouchdbJson setNegociant()
 * @method acCouchdbJson getContrats()
 * @method acCouchdbJson setContrats()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 
 */
 
abstract class BaseSV12 extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'SV12';
    }
    
}