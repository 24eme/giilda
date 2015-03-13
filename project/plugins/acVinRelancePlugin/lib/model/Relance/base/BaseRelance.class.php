<?php
/**
 * BaseRelance
 * 
 * Base model for Relance
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $type_relance
 * @property string $reference
 * @property string $identifiant
 * @property string $region
 * @property string $date_creation
 * @property string $titre
 * @property string $responsable_economique
 * @property acCouchdbJson $emetteur
 * @property acCouchdbJson $origines
 * @property acCouchdbJson $declarant
 * @property acCouchdbJson $verifications

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getTypeRelance()
 * @method string setTypeRelance()
 * @method string getReference()
 * @method string setReference()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getRegion()
 * @method string setRegion()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getTitre()
 * @method string setTitre()
 * @method string getResponsableEconomique()
 * @method string setResponsableEconomique()
 * @method acCouchdbJson getEmetteur()
 * @method acCouchdbJson setEmetteur()
 * @method acCouchdbJson getOrigines()
 * @method acCouchdbJson setOrigines()
 * @method acCouchdbJson getDeclarant()
 * @method acCouchdbJson setDeclarant()
 * @method acCouchdbJson getVerifications()
 * @method acCouchdbJson setVerifications()
 
 */
 
abstract class BaseRelance extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Relance';
    }
    
}