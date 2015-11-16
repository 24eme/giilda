<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureMouvementEditionLignesForm
 *
 * @author mathurin
 */
class FactureMouvementEditionLignesForm extends acCouchdbObjectForm {

    protected $interpro_id;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $options['interpro_id'];
        parent::__construct($object,  $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->getObject() as $mvt) {           
            $this->embedForm($mvt->getKey(), new FactureMouvementEtablissementEditionLigneForm($mvt,array('interpro_id' => $this->interpro_id)));
        }

        $this->widgetSchema->setNameFormat('facture_mouvement_edition_lignes[%s]');
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $releveNonApurementItemForm) {
            $releveNonApurementItemForm->updateObject($values[$key]);
        }
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if (!array_key_exists($key, $taintedValues)) {
                $this->unEmbedForm($key);
            }
        }
        foreach ($taintedValues as $key => $values) {
            if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                continue;
            }
            $this->embedForm($key, new DRMReleveNonApurementItemForm($this->getObject()->add(),array('keyNonApurement' => $key)));
        }
    }

    public function unEmbedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->getObject()->remove($key);
    }

}
