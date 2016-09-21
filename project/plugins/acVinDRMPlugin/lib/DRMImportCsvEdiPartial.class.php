<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMImportCsvEdi
 *
 * @author mathurin
 */
class DRMImportCsvEdiPartial extends DRMImportCsvEdi {

    protected $configuration = null;
    protected $mouvements = array();
    protected $csvDoc = null;

    public function __construct($file, DRM $drm = null) {
        if(is_null($this->csvDoc)) {
            $this->csvDoc = CSVDRMClient::getInstance()->createOrFindDocFromDRM($file, $drm);
        }
        $this->initConf();
        parent::__construct($file, $drm);
    }

    public function hasErreurs(){
      return $this->csvDoc->hasErreurs();
    }

}
