<?php
/**
 * BaseTemplateFactureCotisation
 * 
 * Base model for TemplateFactureCotisation

 * @property string $modele
 * @property string $callback
 * @property string $libelle
 * @property string $code_comptable
 * @property acCouchdbJson $details

 * @method string getModele()
 * @method string setModele()
 * @method string getCallback()
 * @method string setCallback()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCodeComptable()
 * @method string setCodeComptable()
 * @method acCouchdbJson getDetails()
 * @method acCouchdbJson setDetails()
 
 */

abstract class BaseTemplateFactureCotisation extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'TemplateFacture';
       $this->_tree_class_name = 'TemplateFactureCotisation';
    }
                
}