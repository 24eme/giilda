<?php
/**
 * BaseCompte
 * 
 * Base model for Compte
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $num_interne
 * @property string $civilite
 * @property string $prenom
 * @property string $nom
 * @property string $nom_a_afficher
 * @property string $fonction
 * @property string $commentaire
 * @property string $mot_de_passe
 * @property string $login
 * @property string $date_modification
 * @property acCouchdbJson $origines
 * @property string $id_societe
 * @property string $compte_type
 * @property string $adresse_societe
 * @property string $adresse
 * @property string $adresse_complementaire
 * @property string $code_postal
 * @property string $commune
 * @property string $pays
 * @property string $insee
 * @property string $cedex
 * @property string $email
 * @property string $telephone_perso
 * @property string $telephone_bureau
 * @property string $telephone_mobile
 * @property string $fax
 * @property string $site_internet
 * @property string $raison_sociale_societe
 * @property string $type_societe
 * @property string $teledeclaration_active
 * @property string $gecos
 * @property string $lat
 * @property string $lon
 * @property acCouchdbJson $societe_informations
 * @property acCouchdbJson $etablissement_informations
 * @property string $interpro
 * @property string $statut
 * @property acCouchdbJson $groupes
 * @property acCouchdbJson $tags
 * @property acCouchdbJson $droits
 * @property acCouchdbJson $delegation
 * @property acCouchdbJson $alternative_logins

 * @method string getId()
 * @method string setId()
 * @method string getRev()
 * @method string setRev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getNumInterne()
 * @method string setNumInterne()
 * @method string getCivilite()
 * @method string setCivilite()
 * @method string getPrenom()
 * @method string setPrenom()
 * @method string getNom()
 * @method string setNom()
 * @method string getNomAAfficher()
 * @method string setNomAAfficher()
 * @method string getFonction()
 * @method string setFonction()
 * @method string getCommentaire()
 * @method string setCommentaire()
 * @method string getMotDePasse()
 * @method string setMotDePasse()
 * @method string getLogin()
 * @method string setLogin()
 * @method string getDateModification()
 * @method string setDateModification()
 * @method acCouchdbJson getOrigines()
 * @method acCouchdbJson setOrigines()
 * @method string getIdSociete()
 * @method string setIdSociete()
 * @method string getCompteType()
 * @method string setCompteType()
 * @method string getAdresseSociete()
 * @method string setAdresseSociete()
 * @method string getAdresse()
 * @method string setAdresse()
 * @method string getAdresseComplementaire()
 * @method string setAdresseComplementaire()
 * @method string getCodePostal()
 * @method string setCodePostal()
 * @method string getCommune()
 * @method string setCommune()
 * @method string getPays()
 * @method string setPays()
 * @method string getInsee()
 * @method string setInsee()
 * @method string getCedex()
 * @method string setCedex()
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
 * @method string getRaisonSocialeSociete()
 * @method string setRaisonSocialeSociete()
 * @method string getTypeSociete()
 * @method string setTypeSociete()
 * @method string getTeledeclarationActive()
 * @method string setTeledeclarationActive()
 * @method string getGecos()
 * @method string setGecos()
 * @method string getLat()
 * @method string setLat()
 * @method string getLon()
 * @method string setLon()
 * @method acCouchdbJson getSocieteInformations()
 * @method acCouchdbJson setSocieteInformations()
 * @method acCouchdbJson getEtablissementInformations()
 * @method acCouchdbJson setEtablissementInformations()
 * @method string getInterpro()
 * @method string setInterpro()
 * @method string getStatut()
 * @method string setStatut()
 * @method acCouchdbJson getGroupes()
 * @method acCouchdbJson setGroupes()
 * @method acCouchdbJson getTags()
 * @method acCouchdbJson setTags()
 * @method acCouchdbJson getDroits()
 * @method acCouchdbJson setDroits()
 * @method acCouchdbJson getDelegation()
 * @method acCouchdbJson setDelegation()
 * @method acCouchdbJson getAlternativeLogins()
 * @method acCouchdbJson setAlternativeLogins()
 
 */
 
abstract class BaseCompte extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Compte';
    }
    
}