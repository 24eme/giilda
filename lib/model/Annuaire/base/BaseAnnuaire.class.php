<?php
/**
 * BaseAnnuaire
 * 
 * Base model for Annuaire
 *
 * @property string $_id
 * @property string $_rev
 * @property AnnuaireAcheteur $acheteurs
 * @property AnnuaireVendeur $vendeurs
 * @property string $type

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method AnnuaireAcheteur getAcheteurs()
 * @method AnnuaireAcheteur setAcheteurs()
 * @method AnnuaireVendeur getVendeurs()
 * @method AnnuaireVendeur setVendeurs()
 * @method string getType()
 * @method string setType()
 
 */
 
abstract class BaseAnnuaire extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Annuaire';
    }
    
}