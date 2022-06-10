<?php

class DRMAddProduitByInaoForm extends acCouchdbForm {

    public function configure() {
        $this->setWidgets(array(
            'inao' => new bsWidgetFormInput(),
            'denomination_complementaire' => new bsWidgetFormInput()
        ));
        $this->widgetSchema->setLabels(array(
            'inao' => 'Code INAO',
            'denomination_complementaire' => "Libellé produit"
        ));

        $this->setValidators(array(
            'inao' => new sfValidatorString(array('required' => true)),
            'denomination_complementaire' => new sfValidatorString(array('required' => true)),
        ));

        $this->widgetSchema->setNameFormat('add_produit_inao[%s]');
    }

    public function save() {
        $values = $this->getValues();

        $this->getDocument()->addProduitByInao($values["inao"], $values["denomination_complementaire"]);
        $this->getDocument()->save();
    }

}
