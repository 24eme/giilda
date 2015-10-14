<?php
/**
 * BaseGeneration
 * 
 * Base model for Generation
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $date_emission
 * @property string $nb_documents
 * @property string $type_document
 * @property string $somme
 * @property string $statut
 * @property acCouchdbJson $fichiers
 * @property acCouchdbJson $documents

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getDateEmission()
 * @method string setDateEmission()
 * @method string getNbDocuments()
 * @method string setNbDocuments()
 * @method string getTypeDocument()
 * @method string setTypeDocument()
 * @method string getSomme()
 * @method string setSomme()
 * @method string getStatut()
 * @method string setStatut()
 * @method acCouchdbJson getFichiers()
 * @method acCouchdbJson setFichiers()
 * @method acCouchdbJson getDocuments()
 * @method acCouchdbJson setDocuments()
 
 */
 
abstract class BaseGeneration extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Generation';
    }
    
}