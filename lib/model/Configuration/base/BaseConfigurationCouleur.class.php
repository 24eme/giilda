<?php
/**
 * BaseConfigurationCouleur
 * 
 * Base model for ConfigurationCouleur

 * @property string $libelle
 * @property string $format_libelle
 * @property string $code
 * @property string $code_produit
 * @property string $code_comptable
 * @property string $code_douane
 * @property acCouchdbJson $interpro
 * @property acCouchdbJson $departements
 * @property ConfigurationDetail $detail
 * @property acCouchdbJson $cepages

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getFormatLibelle()
 * @method string setFormatLibelle()
 * @method string getCode()
 * @method string setCode()
 * @method string getCodeProduit()
 * @method string setCodeProduit()
 * @method string getCodeComptable()
 * @method string setCodeComptable()
 * @method string getCodeDouane()
 * @method string setCodeDouane()
 * @method acCouchdbJson getInterpro()
 * @method acCouchdbJson setInterpro()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method ConfigurationDetail getDetail()
 * @method ConfigurationDetail setDetail()
 * @method acCouchdbJson getCepages()
 * @method acCouchdbJson setCepages()
 
 */

abstract class BaseConfigurationCouleur extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCouleur';
    }
                
}