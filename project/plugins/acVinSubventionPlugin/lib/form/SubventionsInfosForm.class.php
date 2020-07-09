<?php

class SubventionsInfosForm extends acCouchdbForm {

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {

        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        foreach($this->getDocument()->infos as $categorie => $items) {

            $formCategorie = new BaseForm();

            foreach($items as $key => $item) {
                if($item instanceof acCouchdbJson) {
                    $item->add();
                    $formItem = new BaseForm();
                    foreach($item as $index => $indexItems) {
                        $formTableaux = new BaseForm();
                        foreach($indexItems as $subkey => $subitem) {
                            $label = $item->getInfosSchemaItem($subkey, "label");
                            $help = $item->getInfosSchemaItem($subkey, "unite");

                            $formTableaux->setWidget($subkey, new bsWidgetFormInput());
                            $formTableaux->getWidget($subkey)->setLabel($label);
                            $formTableaux->setValidator($subkey, new sfValidatorString(array('required' => false)));
                            if($help) {
                                $formTableaux->getWidgetSchema()->setHelp($subkey, $help);
                            }
                            $formTableaux->setDefault($subkey, $subitem);
                        }
                        $formItem->embedForm($index, $formTableaux);
                    }
                    $formCategorie->embedForm($key, $formItem);
                    continue;
                }

                $label = $items->getInfosSchemaItem($key, "label");
                $help = $items->getInfosSchemaItem($key, "unite");

                $formCategorie->setWidget($key, new bsWidgetFormInput());
                $formCategorie->getWidget($key)->setLabel($label);
                $formCategorie->setValidator($key, new sfValidatorString(array('required' => false)));
                if($help) {
                    $formCategorie->getWidgetSchema()->setHelp($key, $help);
                }
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
