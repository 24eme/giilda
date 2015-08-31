<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DRMMouvementsValidationForm extends acCouchdbObjectForm {

    private $_drm = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->_drm = $object;
        parent::__construct($this->_drm, $options, $CSRFSecret);
    }

    public function configure() {

        $this->widgetSchema->setNameFormat('drmMouvementsValidation[%s]');
    }

    protected function doUpdateObject($values) {
        $this->_drm->etape = DRMClient::ETAPE_CRD;
        $this->_drm->save();
    }
}
    