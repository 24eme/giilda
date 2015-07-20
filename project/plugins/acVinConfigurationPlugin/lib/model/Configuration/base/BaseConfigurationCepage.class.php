<?php
/**
 * BaseConfigurationCepage
 * 
 * Base model for ConfigurationCepage

 * @property string $libelle
 * @property string $format_libelle
 * @property string $code
 * @property string $code_produit
 * @property string $code_comptable
 * @property string $code_douane
 * @property acCouchdbJson $interpro

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
 
 */

abstract class BaseConfigurationCepage extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCepage';
    }
                
}