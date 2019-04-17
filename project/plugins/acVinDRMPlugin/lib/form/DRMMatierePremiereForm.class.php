<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMAnnexesForm
 *
 * @author mathurin
 */
class DRMMatierePremiereForm extends acCouchdbForm {

    private $detail = null;

    public function __construct(acCouchdbJson $detail, $options = array(), $CSRFSecret = null) {
        $this->detail = $detail;

        parent::__construct($detail->getDocument(), array(), $options, $CSRFSecret);
    }

    public function configure() {


    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);


    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

    }

}
