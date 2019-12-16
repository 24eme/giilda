<?php

class DRMDetailExportItemForm extends DRMESDetailsItemForm {

    protected $detail = null;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->detail = $object;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function getFormName() {

        return "drm_detail_export_item";
    }

    public function getIdentifiantChoices() {
        $country_list = ConfigurationClient::getInstance()->getCountryList();
        if($this->detail->getDocument()->isNegoce()){
          $country_list = array_merge(array("AUTRE" => "Pays Indéterminé"), $country_list);
        }
        return $country_list;
    }

    public function getPostValidatorClass() {

        return 'DRMDetailExportItemValidator';
    }

}
