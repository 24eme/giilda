<?php

class DRMDetailExportItemValidator extends DrmDetailItemValidator {

    protected function doClean($values) {
        $exportDetailConfig = DRMConfiguration::getInstance()->getExportDetail();
        if (!in_array('identifiant', $exportDetailConfig['required'])) {
            if (!(isset($values['identifiant']) || $values['identifiant'])) {
                $values['identifiant'] = DRMClient::DETAIL_EXPORT_PAYS_DEFAULT;
            }
            }
        return parent::doClean($values);
    }

}
