<?php

class SubventionsInfosForm extends acCouchdbForm {

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {

        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        foreach($this->getDocument()->getInfosSchema() as $categorie => $items) {
            $formCategorie = new BaseForm();

            foreach($items as $item) {
                if($item == '*') {
                    for($i = 0; $i < 5; $i++) {
                        $formCategorie->setWidget('key_'.$i, new bsWidgetFormInput());
                        $formCategorie->getWidget('key_'.$i)->setLabel($item);
                        $formCategorie->setValidator('key_'.$i, new sfValidatorString());

                        $formCategorie->setWidget('value_'.$i, new bsWidgetFormInput());
                        $formCategorie->getWidget('value_'.$i)->setLabel($item);
                        $formCategorie->setValidator('value_'.$i, new sfValidatorString());
                    }

                    continue;
                }

                $formCategorie->setWidget($item, new bsWidgetFormInput());
                $formCategorie->getWidget($item)->setLabel($item);
                $formCategorie->setValidator($item, new sfValidatorString(array('required' => false)));

                $this->embedForm($categorie, $formCategorie);
            }
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
