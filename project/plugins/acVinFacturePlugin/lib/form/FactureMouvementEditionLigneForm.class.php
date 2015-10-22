<?php

class FactureMouvementEditionLigneForm extends acCouchdbObjectForm {

    public function configure() {
        $this->setWidget("identifiant_analytique", new sfWidgetFormInput());
        $this->setWidget("identifiant", new sfWidgetFormInput());
        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget("quantite", new sfWidgetFormInputFloat());
        $this->setWidget("prix_unitaire", new sfWidgetFormInputFloat());


      

        $this->setValidator("identifiant", new sfValidatorString(array("required" => true)));
        $this->setValidator("identifiant_analytique", new sfValidatorString(array('required' => true)));
        $this->setValidator("libelle", new sfValidatorString(array('required' => true)));
        $this->setValidator("quantite", new sfValidatorNumber(array('required' => true)));                
        $this->setValidator("prix_unitaire", new sfValidatorNumber(array('required' => true)));


//        $this->validatorSchema->setPreValidator(new FactureEditionLigneValidator());

        $this->widgetSchema->setNameFormat('facture_mouvment_edition_ligne[%s]');
    }

}
