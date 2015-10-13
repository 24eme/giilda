<?php
/**
 * BaseTemplateFactureCotisationDetail
 * 
 * Base model for TemplateFactureCotisationDetail

 * @property string $modele
 * @property string $prix
 * @property string $tva
 * @property string $libelle
 * @property string $variable
 * @property string $tranche
 * @property string $reference
 * @property string $callback
 * @property string $depart
 * @property string $minimum
 * @property string $complement
 * @property string $complement_libelle
 * @property acCouchdbJson $intervalles
 * @property acCouchdbJson $docs

 * @method string getModele()
 * @method string setModele()
 * @method string getPrix()
 * @method string setPrix()
 * @method string getTva()
 * @method string setTva()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getVariable()
 * @method string setVariable()
 * @method string getTranche()
 * @method string setTranche()
 * @method string getReference()
 * @method string setReference()
 * @method string getCallback()
 * @method string setCallback()
 * @method string getDepart()
 * @method string setDepart()
 * @method string getMinimum()
 * @method string setMinimum()
 * @method string getComplement()
 * @method string setComplement()
 * @method string getComplementLibelle()
 * @method string setComplementLibelle()
 * @method acCouchdbJson getIntervalles()
 * @method acCouchdbJson setIntervalles()
 * @method acCouchdbJson getDocs()
 * @method acCouchdbJson setDocs()
 
 */

abstract class BaseTemplateFactureCotisationDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'TemplateFacture';
       $this->_tree_class_name = 'TemplateFactureCotisationDetail';
    }
                
}