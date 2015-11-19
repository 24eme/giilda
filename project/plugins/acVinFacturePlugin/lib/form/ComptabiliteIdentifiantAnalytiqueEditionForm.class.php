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

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget("identifiant_analytique", new sfWidgetFormInput());
        $this->setWidget("identifiant_analytique_libelle", new sfWidgetFormInput());
        $this->setWidget("identifiant_analytique_libelle_compta", new sfWidgetFormInput());

        $this->setWidget("produit_identifiant_analytique", new sfWidgetFormInputHidden());
        $this->setWidget("montant_tva", new sfWidgetFormInputHidden());
        $this->setWidget("montant_ht", new sfWidgetFormInputHidden());


        $this->setValidator("libelle", new sfValidatorString(array("required" => false)));
        $this->setValidator("produit_identifiant_analytique", new sfValidatorString(array('required' => false)));
        $this->setValidator("montant_tva", new sfValidatorNumber(array('required' => false)));
        $this->setValidator("montant_ht", new sfValidatorNumber(array('required' => false)));


        $this->widgetSchema->setNameFormat('comptabilite_identifiant_analytique[%s]');
    }

}
