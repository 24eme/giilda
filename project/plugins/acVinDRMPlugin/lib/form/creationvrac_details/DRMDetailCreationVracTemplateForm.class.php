<?php

class DRMDetailCreationVracTemplateForm extends DRMDetailCreationVracForm {

    protected $isTeledeclarationMode = null;

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        parent::__construct($details, $defaults, $options, $CSRFSecret);
        unset($this['_revision']);
    }

    public function configure() {
        $newCreationVrac = DRMESDetailCreationVrac::freeInstance($this->details->getDocument());
        if(preg_match("/^creationvrac_details$/",$this->details->getKey())){
            $newCreationVrac->type_contrat = VracClient::TYPE_TRANSACTION_VIN_VRAC;
        }
        if(preg_match("/^creationvractirebouche_details/",$this->details->getKey())){
            $newCreationVrac->type_contrat = VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE;
        }
        $item_form_class = $this->getFormItemClass();
        $itemForm = $this->embedForm('var---nbItem---', new $item_form_class($this->details->addDetail($newCreationVrac), array('isTeledeclarationMode' => $this->isTeledeclarationMode)));
        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function getFormTemplate() {

        return $this['var---nbItem---'];
    }

}
