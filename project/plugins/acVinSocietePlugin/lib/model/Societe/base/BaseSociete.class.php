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
 * @property string $mot_de_passe
 * @property string $type_societe
 * @property string $raison_sociale
 * @property string $raison_sociale_abregee
 * @property string $statut
 * @property string $code_comptable_client
 * @property string $code_comptable_fournisseur
 * @property string $teledeclaration_email
 * @property string $paiement_douane_moyen
 * @property string $paiement_douane_frequence
 * @property acCouchdbJson $type_fournisseur
 * @property string $code_naf
 * @property string $siret
 * @property string $no_tva_intracommunautaire
 * @property string $insee
 * @property string $interpro
 * @property string $date_modification
 * @property string $date_creation
 * @property string $email
 * @property string $telephone_perso
 * @property string $telephone_bureau
 * @property string $telephone_mobile
 * @property string $fax
 * @property string $site_internet
 * @property string $lat
 * @property string $lon
 * @property string $commentaire
 * @property acCouchdbJson $siege
 * @property string $cooperative
 * @property acCouchdbJson $enseignes
 * @property acCouchdbJson $contacts
 * @property acCouchdbJson $etablissements

 * @method string getId()
 * @method string setId()
 * @method string getRev()
 * @method string setRev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getMotDePasse()
 * @method string setMotDePasse()
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
 * @method string getTeledeclarationEmail()
 * @method string setTeledeclarationEmail()
 * @method string getPaiementDouaneMoyen()
 * @method string setPaiementDouaneMoyen()
 * @method string getPaiementDouaneFrequence()
 * @method string setPaiementDouaneFrequence()
 * @method acCouchdbJson getTypeFournisseur()
 * @method acCouchdbJson setTypeFournisseur()
 * @method string getCodeNaf()
 * @method string setCodeNaf()
 * @method string getSiret()
 * @method string setSiret()
 * @method string getNoTvaIntracommunautaire()
 * @method string setNoTvaIntracommunautaire()
 * @method string getInsee()
 * @method string setInsee()
 * @method string getInterpro()
 * @method string setInterpro()
 * @method string getDateModification()
 * @method string setDateModification()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getEmail()
 * @method string setEmail()
 * @method string getTelephonePerso()
 * @method string setTelephonePerso()
 * @method string getTelephoneBureau()
 * @method string setTelephoneBureau()
 * @method string getTelephoneMobile()
 * @method string setTelephoneMobile()
 * @method string getFax()
 * @method string setFax()
 * @method string getSiteInternet()
 * @method string setSiteInternet()
 * @method string getLat()
 * @method string setLat()
 * @method string getLon()
 * @method string setLon()
 * @method string getCommentaire()
 * @method string setCommentaire()
 * @method acCouchdbJson getSiege()
 * @method acCouchdbJson setSiege()
 * @method string getCooperative()
 * @method string setCooperative()
 * @method acCouchdbJson getEnseignes()
 * @method acCouchdbJson setEnseignes()
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
