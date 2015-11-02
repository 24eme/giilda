<?php
/**
 * BaseMouvementsFacture
 * 
 * Base model for MouvementsFacture
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $campagne
 * @property string $date
 * @property string $identifiant
 * @property string $libelle
 * @property acCouchdbJson $valide
 * @property acCouchdbJson $mouvements

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getDate()
 * @method string setDate()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 * @method acCouchdbJson getMouvements()
 * @method acCouchdbJson setMouvements()
 
 */
 
abstract class BaseMouvementsFacture extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'MouvementsFacture';
    }
    
}