<?php
/**
 * BaseAnnuaire
 * 
 * Base model for Annuaire
 *
 * @property string $_id
 * @property string $_rev
 * @property AnnuaireRecoltant $recoltants
 * @property AnnuaireNegociant $negociants
 * @property AnnuaireRepresentant $representants
 * @property AnnuaireCaveCooperative $caves_cooperatives
 * @property acCouchdbJson $commerciaux
 * @property string $identifiant
 * @property string $type

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method AnnuaireRecoltant getRecoltants()
 * @method AnnuaireRecoltant setRecoltants()
 * @method AnnuaireNegociant getNegociants()
 * @method AnnuaireNegociant setNegociants()
 * @method AnnuaireRepresentant getRepresentants()
 * @method AnnuaireRepresentant setRepresentants()
 * @method AnnuaireCaveCooperative getCavesCooperatives()
 * @method AnnuaireCaveCooperative setCavesCooperatives()
 * @method acCouchdbJson getCommerciaux()
 * @method acCouchdbJson setCommerciaux()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getType()
 * @method string setType()
 
 */
 
abstract class BaseAnnuaire extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Annuaire';
    }
    
}