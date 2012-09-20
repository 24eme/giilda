<?php
/**
 * BaseFacture
 * 
 * Base model for Facture
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $date_emission
 * @property string $date_facturation
 * @property string $campagne
 * @property integer $nb_page
 * @property string $statut
 * @property acCouchdbJson $emetteur
 * @property string $client_identifiant
 * @property string $client_reference
 * @property acCouchdbJson $client
 * @property float $total_ht
 * @property float $total_ttc
 * @property acCouchdbJson $lignes
 * @property acCouchdbJson $echeances
 * @property acCouchdbJson $origines

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
 * @method string getDateFacturation()
 * @method string setDateFacturation()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method integer getNbPage()
 * @method integer setNbPage()
 * @method string getStatut()
 * @method string setStatut()
 * @method acCouchdbJson getEmetteur()
 * @method acCouchdbJson setEmetteur()
 * @method string getClientIdentifiant()
 * @method string setClientIdentifiant()
 * @method string getClientReference()
 * @method string setClientReference()
 * @method acCouchdbJson getClient()
 * @method acCouchdbJson setClient()
 * @method float getTotalHt()
 * @method float setTotalHt()
 * @method float getTotalTtc()
 * @method float setTotalTtc()
 * @method acCouchdbJson getLignes()
 * @method acCouchdbJson setLignes()
 * @method acCouchdbJson getEcheances()
 * @method acCouchdbJson setEcheances()
 * @method acCouchdbJson getOrigines()
 * @method acCouchdbJson setOrigines()
 
 */
 
abstract class BaseFacture extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Facture';
    }
    
}