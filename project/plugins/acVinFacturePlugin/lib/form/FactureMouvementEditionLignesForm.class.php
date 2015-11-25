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
    protected $newUniqId;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $options['interpro_id'];
        $this->newUniqId = uniqid();
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->getObject() as $mvt) {
            $this->embedForm($mvt->getKey(), new FactureMouvementEtablissementEditionLigneForm($mvt, array('interpro_id' => $this->interpro_id)));
        }
        $this->widgetSchema->setNameFormat('facture_mouvement_edition_lignes[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $factureMouvementItemForm) {
            $factureMouvementItemForm->updateObject($values[$key]);
        }
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if ($form instanceof FactureMouvementEtablissementEditionLigneForm) {
                if (isset($taintedValues[$key])) {
                    $form->bind($taintedValues[$key], $taintedFiles[$key]);
                    $this->updateEmbedForm($key, $form);
                }
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

}
