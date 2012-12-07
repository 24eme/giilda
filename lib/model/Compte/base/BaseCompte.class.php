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
 * @property string $civilite
 * @property string $prenom
 * @property string $nom
 * @property string $fonction
 * @property string $commentaire
 * @property string $id_societe
 * @property string $adresse
 * @property string $adresse_complementaire
 * @property string $code_postal
 * @property string $commune
 * @property string $cedex
 * @property string $pays
 * @property string $email
 * @property string $telephone_perso
 * @property string $telephone_bureau
 * @property string $telephone_mobile
 * @property string $fax
 * @property string $nom_a_afficher
 * @property acCouchdbJson $tags

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getCivilite()
 * @method string setCivilite()
 * @method string getPrenom()
 * @method string setPrenom()
 * @method string getNom()
 * @method string setNom()
 * @method string getFonction()
 * @method string setFonction()
 * @method string getCommentaire()
 * @method string setCommentaire()
 * @method string getIdSociete()
 * @method string setIdSociete()
 * @method string getAdresse()
 * @method string setAdresse()
 * @method string getAdresseComplementaire()
 * @method string setAdresseComplementaire()
 * @method string getCodePostal()
 * @method string setCodePostal()
 * @method string getCommune()
 * @method string setCommune()
 * @method string getCedex()
 * @method string setCedex()
 * @method string getPays()
 * @method string setPays()
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
 * @method string getNomAAfficher()
 * @method string setNomAAfficher()
 * @method acCouchdbJson getTags()
 * @method acCouchdbJson setTags()
 
 */
 
abstract class BaseCompte extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Compte';
    }
    
}