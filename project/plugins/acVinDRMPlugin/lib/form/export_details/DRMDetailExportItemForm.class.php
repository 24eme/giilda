<?php

class DRMDetailExportItemForm extends DRMESDetailsItemForm {

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function getFormName() {

        return "drm_detail_export_item";
    }

    public function getIdentifiantChoices() {
        $country_list = ConfigurationClient::getInstance()->getCountryList();
        if($object->getDocument()->isNegoce()){
          $country_list = array_merge(array("AUTRE": "Pays Indéterminé"), $country_list);
        }
        return $country_list;
    }

    public function getPostValidatorClass() {

        return 'DRMDetailExportItemValidator';
    }

}
