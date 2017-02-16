<?php

class DRMDetailVracTemplateForm extends DRMDetailVracForm {

    protected $isTeledeclarationMode = null;
    protected $details = null;

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        $this->details = $details;
        parent::__construct($details, $defaults, $options, $CSRFSecret);
        unset($this['_revision']);
    }

    public function configure() {
        $newVrac = DRMESDetailVrac::freeInstance($this->details->getDocument());
        $item_form_class = $this->getFormItemClass();
        $this->embedForm('var---nbItem---', new $item_form_class($this->details->addDetail($newVrac), array('isTeledeclarationMode' => $this->isTeledeclarationMode,'details' => $this->details)));
        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function getFormTemplate() {

        return $this['var---nbItem---'];
    }

}
