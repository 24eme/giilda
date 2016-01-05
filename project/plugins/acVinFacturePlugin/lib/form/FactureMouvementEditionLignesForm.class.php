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
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->getObject() as $keyMvt => $mvt) {
            $this->embedForm($keyMvt, new FactureMouvementEtablissementEditionLigneForm($mvt, array('interpro_id' => $this->interpro_id, 'keyMvt' => $keyMvt)));
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
            if (!array_key_exists($key, $taintedValues)) {
                $this->unEmbedForm($key);
            }
        }
        foreach ($taintedValues as $key => $values) {
            if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                continue;
            }
            $this->embedForm($key, new FactureMouvementEditionLigneForm($key, array('interpro_id' => $this->interpro_id, 'uniqkeyMvt' => $key)));
        }
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

}
