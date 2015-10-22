<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureMouvementsEditionForm
 *
 * @author mathurin
 */
class FactureMouvementsEditionForm extends acCouchdbObjectForm {

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {

        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget('date', new bsWidgetFormInputDate());

        $this->setValidator("libelle", new sfValidatorString(array("required" => true)));
        $this->setValidator('date', new sfValidatorString(array('required' => false)));
        
        $this->getObject()->mouvements->add("nouvelle");

        $this->embedForm('mouvements', new FactureMouvementEditionLignesForm($this->getObject()->mouvements));

        $this->widgetSchema->setNameFormat('facture_mouvements_edition[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
//
//        if ($this->getObject()->lignes->exist("nouvelle")) {
//            $newLine = $this->getObject()->lignes->get("nouvelle")->toArray(true, false);
//            $this->getObject()->lignes->remove("nouvelle");
//            $this->getObject()->lignes->add(uniqid(), $newLine);
//        }
//
//        $this->getObject()->lignes->cleanLignes();
//        $this->getObject()->updateTotaux();
    }

}
