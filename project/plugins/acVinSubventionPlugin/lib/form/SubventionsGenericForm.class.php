<?php

class SubventionsGenericForm extends acCouchdbForm {

    protected $nodeName = null;

    public function __construct(acCouchdbDocument $doc, $nodeName, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->nodeName = $nodeName;
        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $name = $this->nodeName;
        $node = $this->getDocument()->$name;
        foreach($node as $categorie => $items) {

            $formCategorie = new BaseForm();

            foreach($items as $key => $item) {
                //var_dump(get_class($items)); exit;
                $label = $items->getSchemaItem($key, "label");
                $help = $items->getSchemaItem($key, "help");

                $widgetClass = "bsWidgetFormInput";
                $validatorClass = "sfValidatorString";

                if($items->getSchemaItem($key, "type") == "float") {
                    $widgetClass = "bsWidgetFormInputFloat";
                    $validatorClass = "sfValidatorNumber";
                }

                $formCategorie->setWidget($key, new $widgetClass());
                $formCategorie->getWidget($key)->setLabel($label);
                $formCategorie->setValidator($key, new $validatorClass(array('required' => false)));
                $formCategorie->setDefault($key, $item);

                if($help) {
                    $formCategorie->getWidgetSchema()->setHelp($key, $help);
                }
            }

            $this->embedForm($categorie, $formCategorie);
        }

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('subvention_'.$name.'[%s]');
    }

    public function save() {
        $values = $this->getValues();
        $name = $this->nodeName;
        $this->getDocument()->remove($name);
        $node = $this->getDocument()->add($name);
        foreach($values as $categorie => $items) {
            if(!is_array($items)) {
                continue;
            }
            foreach($items as $key => $value) {
                $node->add($categorie)->add($key, $value);
            }
        }

        $this->getDocument()->save();
    }

}
