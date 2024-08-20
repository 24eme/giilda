<?php
/**
 * BaseEtablissementChais
 * 
 * Base model for EtablissementChais

 * @property string $nom
 * @property string $adresse
 * @property string $commune
 * @property string $code_postal
 * @property string $partage
 * @property string $lat
 * @property string $lon
 * @property acCouchdbJson $attributs

 * @method string getNom()
 * @method string setNom()
 * @method string getAdresse()
 * @method string setAdresse()
 * @method string getCommune()
 * @method string setCommune()
 * @method string getCodePostal()
 * @method string setCodePostal()
 * @method string getPartage()
 * @method string setPartage()
 * @method string getLat()
 * @method string setLat()
 * @method string getLon()
 * @method string setLon()
 * @method acCouchdbJson getAttributs()
 * @method acCouchdbJson setAttributs()
 
 */

abstract class BaseEtablissementChais extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Etablissement';
       $this->_tree_class_name = 'EtablissementChais';
    }
                
}