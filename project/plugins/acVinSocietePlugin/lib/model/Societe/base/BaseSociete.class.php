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
 * @property string $type_societe
 * @property string $raison_sociale
 * @property string $raison_sociale_abregee
 * @property string $statut
 * @property string $code_comptable_client
 * @property string $code_comptable_fournisseur
 * @property string $code_naf
 * @property string $siret
 * @property string $interpro
 * @property string $date_modification
 * @property string $date_creation
 * @property string $no_tva_intracommunautaire
 * @property string $commentaire
 * @property acCouchdbJson $siege
 * @property string $cooperative
 * @property acCouchdbJson $enseignes
 * @property string $compte_societe
 * @property acCouchdbJson $contacts
 * @property acCouchdbJson $etablissements

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getTypeSociete()
 * @method string setTypeSociete()
 * @method string getRaisonSociale()
 * @method string setRaisonSociale()
 * @method string getRaisonSocialeAbregee()
 * @method string setRaisonSocialeAbregee()
 * @method string getStatut()
 * @method string setStatut()
 * @method string getCodeComptableClient()
 * @method string setCodeComptableClient()
 * @method string getCodeComptableFournisseur()
 * @method string setCodeComptableFournisseur()
 * @method string getCodeNaf()
 * @method string setCodeNaf()
 * @method string getSiret()
 * @method string setSiret()
 * @method string getInterpro()
 * @method string setInterpro()
 * @method string getDateModification()
 * @method string setDateModification()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getNoTvaIntracommunautaire()
 * @method string setNoTvaIntracommunautaire()
 * @method string getCommentaire()
 * @method string setCommentaire()
 * @method acCouchdbJson getSiege()
 * @method acCouchdbJson setSiege()
 * @method string getCooperative()
 * @method string setCooperative()
 * @method acCouchdbJson getEnseignes()
 * @method acCouchdbJson setEnseignes()
 * @method string getCompteSociete()
 * @method string setCompteSociete()
 * @method acCouchdbJson getContacts()
 * @method acCouchdbJson setContacts()
 * @method acCouchdbJson getEtablissements()
 * @method acCouchdbJson setEtablissements()
 
 */
 
abstract class BaseSociete extends CompteGenerique {

    public function getDocumentDefinitionModel() {
        return 'Societe';
    }
    
}
