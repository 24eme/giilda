<?php

class SubventionsInfosForm extends acCouchdbForm {

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {

        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        foreach($this->getDocument()->infos as $categorie => $items) {

            $formCategorie = new BaseForm();

            foreach($items as $key => $item) {
                $label = $items->getInfosSchemaItem($key, "label");

                $widgetClass = "bsWidgetFormInput";
                $validatorClass = "sfValidatorString";

                if($items->getInfosSchemaItem($key, "type") == "float") {
                    $widgetClass = "bsWidgetFormInputFloat";
                    $validatorClass = "sfValidatorNumber";
                }

                $formCategorie->setWidget($key, new $widgetClass());
                $formCategorie->getWidget($key)->setLabel($label);
                $formCategorie->setValidator($key, new $validatorClass(array('required' => false)));
                $formCategorie->setDefault($key, $item);
            }

            $this->embedForm($categorie, $formCategorie);
        }

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('subvention_infos[%s]');
    }

    public function save() {
        $values = $this->getValues();

        $this->getDocument()->remove('infos');
        $this->getDocument()->add('infos');

        foreach($values as $categorie => $items) {
            if(!is_array($items)) {
                continue;
            }
            foreach($items as $key => $value) {
                $this->getDocument()->infos->add($categorie)->add($key, $value);
            }
        }

        $this->getDocument()->save();
    }

}
