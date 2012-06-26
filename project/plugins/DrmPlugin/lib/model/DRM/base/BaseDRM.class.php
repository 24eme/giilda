<?php
/**
 * BaseDRM
 * 
 * Base model for DRM
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $apurement_possible
 * @property string $raison_rectificative
 * @property string $etape
 * @property string $campagne
 * @property string $precedente
 * @property integer $rectificative
 * @property acCouchdbJson $droits
 * @property DRMDeclaration $declaration
 * @property DRMDeclarant $declarant
 * @property acCouchdbJson $declaratif
 * @property string $identifiant
 * @property string $mode_de_saisie
 * @property acCouchdbJson $interpros
 * @property acCouchdbJson $valide
 * @property acCouchdbJson $douane
 * @property acCouchdbJson $editeurs

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getApurementPossible()
 * @method string setApurementPossible()
 * @method string getRaisonRectificative()
 * @method string setRaisonRectificative()
 * @method string getEtape()
 * @method string setEtape()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getPrecedente()
 * @method string setPrecedente()
 * @method integer getRectificative()
 * @method integer setRectificative()
 * @method acCouchdbJson getDroits()
 * @method acCouchdbJson setDroits()
 * @method DRMDeclaration getDeclaration()
 * @method DRMDeclaration setDeclaration()
 * @method DRMDeclarant getDeclarant()
 * @method DRMDeclarant setDeclarant()
 * @method acCouchdbJson getDeclaratif()
 * @method acCouchdbJson setDeclaratif()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getModeDeSaisie()
 * @method string setModeDeSaisie()
 * @method acCouchdbJson getInterpros()
 * @method acCouchdbJson setInterpros()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
 * @method acCouchdbJson getEditeurs()
 * @method acCouchdbJson setEditeurs()
 
 */
 
abstract class BaseDRM extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DRM';
    }
    
}