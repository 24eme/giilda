<?php

class DRMDetailVracItemForm extends DRMESDetailsItemForm {

    public function getFormName() {

        return "drm_detail_vrac_item";
    }

    public function getIdentifiantChoices() {
        
        return array_merge(array("", ""), $this->getProduitDetail()->getContratsVrac());
    }

    public function getPostValidatorClass() {

        return 'DRMDetailVracItemValidator';
    }

}
