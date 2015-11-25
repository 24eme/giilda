<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComptabiliteIdentifiantAnalytiqueEditionForm
 *
 * @author mathurin
 */
class ComptabiliteIdentifiantAnalytiqueEditionForm extends acCouchdbObjectForm {

    public function  __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {


      


        $this->widgetSchema->setNameFormat('comptabilite_identifiant_analytique[%s]');
    }

}
