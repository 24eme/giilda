<?php
/**
 * BaseSubvention
 * 
 * Base model for Subvention
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $operation
 * @property acCouchdbJson $infos
 * @property acCouchdbJson $contact
 * @property acCouchdbJson $produits
 * @property acCouchdbJson $engagements
 * @property string $commentaire
 * @property string $validation_date
 * @property string $signature_date

 * @method string getId()
 * @method string setId()
 * @method string getRev()
 * @method string setRev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getOperation()
 * @method string setOperation()
 * @method acCouchdbJson getInfos()
 * @method acCouchdbJson setInfos()
 * @method acCouchdbJson getContact()
 * @method acCouchdbJson setContact()
 * @method acCouchdbJson getProduits()
 * @method acCouchdbJson setProduits()
 * @method acCouchdbJson getEngagements()
 * @method acCouchdbJson setEngagements()
 * @method string getCommentaire()
 * @method string setCommentaire()
 * @method string getValidationDate()
 * @method string setValidationDate()
 * @method string getSignatureDate()
 * @method string setSignatureDate()
 
 */
 
abstract class BaseSubvention extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Subvention';
    }
    
}