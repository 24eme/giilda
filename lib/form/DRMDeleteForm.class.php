<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMDeleteForm
 *
 * @author mathurin
 */
class DRMDeleteForm extends acCouchdbObjectForm {
  
    private $drm;
    
    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->drm = $drm;
        parent::__construct($drm, $options, $CSRFSecret);
    }
    
    public function configure() {
       $this->widgetSchema->setNameFormat('drmDeleteForm[%s]');
    }
}
