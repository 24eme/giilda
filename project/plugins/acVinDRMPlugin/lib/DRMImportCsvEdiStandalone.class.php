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
class DRMImportCsvEdiStandalone extends DRMImportCsvEdi {
    public function __construct($file, DRM $drm = null) {
        $this->csvDoc = new CSVStandalone();
        $this->csvDoc->identifiant = $drm->identifiant;
        $this->csvDoc->periode = $drm->periode;

        parent::__construct($file, $drm);
    }

    public function getDocRows() {
        return $this->getCsv();
    }

}

class CSVStandalone extends CSVDRM {

    public function save() {

        return false;
    }
}
