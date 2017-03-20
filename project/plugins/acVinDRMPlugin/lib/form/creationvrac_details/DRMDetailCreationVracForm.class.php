<?php

class DRMDetailCreationVracForm extends DRMESDetailsForm {

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($details, $defaults, $options, $CSRFSecret);
    }

    public function getFormName() {

        return 'drm_creationdetail_vrac';
    }

    public function getFormItemClass() {

        return 'DRMDetailCreationVracItemForm';
    }

    public function getFormTemplateClass() {

        return 'DRMDetailCreationVracTemplateForm';
    }

    public function getModelNode(){

        return 'DRMESDetailCreationVrac';
    }


    public function bind(array $taintedValues = null, array $taintedFiles = null) {
      parent::bind($taintedValues,$taintedFiles);
    }

    public function update(){
        parent::update();
        $type_transaction = null;
        if(preg_match("/^creationvrac_details$/",$this->details->getKey())){
            $type_transaction = VracClient::TYPE_TRANSACTION_VIN_VRAC;
        }
        if(preg_match("/^creationvractirebouche_details/",$this->details->getKey())){
            $type_transaction = VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE;
        }
        foreach ($this->details as $key => $detail) {
            $detail->type_contrat = $type_transaction;
        }
    }

}
