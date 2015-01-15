<?php
/**
 * BaseAlerte
 * 
 * Base model for Alerte
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $type_alerte
 * @property string $date_creation
 * @property string $date_relance
 * @property string $date_relance_ar
 * @property string $id_document
 * @property string $declarant_nom
 * @property string $identifiant
 * @property string $libelle_document
 * @property string $region
 * @property string $campagne
 * @property string $type_document
 * @property string $type_relance
 * @property string $statut_courant
 * @property string $date_dernier_statut
 * @property acCouchdbJson $statuts

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getTypeAlerte()
 * @method string setTypeAlerte()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getDateRelance()
 * @method string setDateRelance()
 * @method string getDateRelanceAr()
 * @method string setDateRelanceAr()
 * @method string getIdDocument()
 * @method string setIdDocument()
 * @method string getDeclarantNom()
 * @method string setDeclarantNom()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getLibelleDocument()
 * @method string setLibelleDocument()
 * @method string getRegion()
 * @method string setRegion()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getTypeDocument()
 * @method string setTypeDocument()
 * @method string getTypeRelance()
 * @method string setTypeRelance()
 * @method string getStatutCourant()
 * @method string setStatutCourant()
 * @method string getDateDernierStatut()
 * @method string setDateDernierStatut()
 * @method acCouchdbJson getStatuts()
 * @method acCouchdbJson setStatuts()
 
 */
 
abstract class BaseAlerte extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Alerte';
    }
    
}