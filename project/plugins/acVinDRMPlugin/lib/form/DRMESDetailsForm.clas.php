<?php

abstract class DRMESDetailsForm extends acCouchdbForm {

    protected $details = null;
    protected $isTeledeclarationMode = null;

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        $this->details = $details;
        parent::__construct($this->details->getDocument(),$defaults, $options, $CSRFSecret);
    }

    public function configure() {
        if (!count($this->details)) {
            $this->details->addDetail();
        }

        foreach ($this->details as $key => $item) {
            $form_item_class = $this->getFormItemClass();
            if (!$key) {
                $key = uniqid();
            }
            $form = $this->embedForm($key, new $form_item_class($item,array('isTeledeclarationMode' => $this->isTeledeclarationMode)));
        }
        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if (!array_key_exists($key, $taintedValues)) {
                $this->unembedForm($key);
            }
        }

        foreach ($taintedValues as $key => $values) {
            if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                continue;
            }

            $form_item_class = $this->getFormItemClass();
            $this->embedForm($key, new $form_item_class($this->details->addDetail($key),array('isTeledeclarationMode' => $this->isTeledeclarationMode)));
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function update() {
        //$this->details->clear();
        $details = array();

        foreach ($this->getEmbeddedForms() as $key => $form) {
            $form->updateObject($this->values[$key]);
            $details[] = clone $form->getObject();
        }
        $parent = $this->getDetails()->getParent();
        $key = $this->getDetails()->getKey();
        $parent->remove($key);
        $this->details = $parent->add($key);

        foreach ($this->getDetails() as $identifiant => $detail) {
            if ($this->getDetails()->exist($identifiant)) {
                $this->getDetails()->remove($identifiant);
            }
        }

        foreach ($details as $key => $detail) {
            if($detail->volume){
              $this->getDetails()->addDetail($detail->identifiant, $detail->volume, $detail->date_enlevement, $detail->numero_document, $detail->type_document);
            }
        }
    }

    public function getDetails() {

        return $this->details;
    }

    public function getFormTemplate() {
        $form_template_class = $this->getFormTemplateClass();
        $form = new $form_template_class($this->details,array(),array('isTeledeclarationMode' => $this->isTeledeclarationMode));
        return $form->getFormTemplate();
    }

    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->details->remove($key);
    }

    public abstract function getFormName();

    public abstract function getFormItemClass();

    public abstract function getFormTemplateClass();
}
