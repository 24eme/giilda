<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DRMMouvementsValidationForm extends acCouchdbObjectForm {

    private $_drm = null;
    private $isTeledeclarationMode = false;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->_drm = $object;
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        parent::__construct($this->_drm, $options, $CSRFSecret);
    }

    public function configure() {

        $this->widgetSchema->setNameFormat('drmMouvementsValidation[%s]');
    }

    protected function doUpdateObject($values) {   
        $this->_drm->etape = ($this->isTeledeclarationMode)? DRMClient::ETAPE_CRD : DRMClient::ETAPE_VALIDATION ;
        $this->_drm->save();
    }
}
    