<?php
/**
 * BaseSociete
 * 
 * Base model for Societe
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $raison_sociale
 * @property string $telephone
 * @property string $siret
 * @property string $commune
 * @property string $code_postal
 * @property acCouchdbJson $etablissements

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getRaisonSociale()
 * @method string setRaisonSociale()
 * @method string getTelephone()
 * @method string setTelephone()
 * @method string getSiret()
 * @method string setSiret()
 * @method string getCommune()
 * @method string setCommune()
 * @method string getCodePostal()
 * @method string setCodePostal()
 * @method acCouchdbJson getEtablissements()
 * @method acCouchdbJson setEtablissements()
 
 */
 
abstract class BaseSociete extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Societe';
    }
    
}