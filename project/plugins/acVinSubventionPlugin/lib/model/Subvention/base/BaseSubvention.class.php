<?php
/**
 * BaseSubvention
 * 
 * Base model for Subvention
 *
 * @property string $_id
 * @property string $_rev
 * @property acCouchdbJson $_attachments
 * @property string $type
 * @property string $identifiant
 * @property string $operation
 * @property acCouchdbJson $declarant
 * @property SubventionInfos $infos
 * @property acCouchdbJson $engagements
 * @property string $commentaire
 * @property string $validation_date
 * @property string $signature_date

 * @method string getId()
 * @method string setId()
 * @method string getRev()
 * @method string setRev()
 * @method acCouchdbJson getAttachments()
 * @method acCouchdbJson setAttachments()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getOperation()
 * @method string setOperation()
 * @method acCouchdbJson getDeclarant()
 * @method acCouchdbJson setDeclarant()
 * @method SubventionInfos getInfos()
 * @method SubventionInfos setInfos()
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