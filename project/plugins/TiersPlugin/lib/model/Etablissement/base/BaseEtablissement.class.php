<?php
/**
 * BaseEtablissement
 * 
 * Base model for Etablissement
 *
 * @property string $_id
 * @property string $_rev
 * @property string $identifiant
 * @property string $nom
 * @property string $type
 * @property string $statut
 * @property string $famille
 * @property string $cvi
 * @property string $adresse
 * @property string $commune
 * @property string $code_postal
 * @property string $num_accise
 * @property string $carte_pro
 * @property string $num_tva_intracomm
 * @property acCouchdbJson $domaines

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getNom()
 * @method string setNom()
 * @method string getType()
 * @method string setType()
 * @method string getStatut()
 * @method string setStatut()
 * @method string getFamille()
 * @method string setFamille()
 * @method string getCvi()
 * @method string setCvi()
 * @method string getAdresse()
 * @method string setAdresse()
 * @method string getCommune()
 * @method string setCommune()
 * @method string getCodePostal()
 * @method string setCodePostal()
 * @method string getNumAccise()
 * @method string setNumAccise()
 * @method string getCartePro()
 * @method string setCartePro()
 * @method string getNumTvaIntracomm()
 * @method string setNumTvaIntracomm()
 * @method acCouchdbJson getDomaines()
 * @method acCouchdbJson setDomaines()
 
 */
 
abstract class BaseEtablissement extends _Tiers {

    public function getDocumentDefinitionModel() {
        return 'Etablissement';
    }
    
}