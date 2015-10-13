<?php
/**
 * BaseTemplateFacture
 * 
 * Base model for TemplateFacture
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $campagne
 * @property string $template
 * @property string $libelle
 * @property acCouchdbJson $docs
 * @property acCouchdbJson $arguments
 * @property acCouchdbJson $cotisations

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getTemplate()
 * @method string setTemplate()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getDocs()
 * @method acCouchdbJson setDocs()
 * @method acCouchdbJson getArguments()
 * @method acCouchdbJson setArguments()
 * @method acCouchdbJson getCotisations()
 * @method acCouchdbJson setCotisations()
 
 */
 
abstract class BaseTemplateFacture extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'TemplateFacture';
    }
    
}