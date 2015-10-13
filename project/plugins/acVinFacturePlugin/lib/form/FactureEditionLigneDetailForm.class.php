<?php

class FactureEditionLigneDetailForm extends acCouchdbObjectForm {

    public function configure()
    {
        $this->setWidget("quantite", new sfWidgetFormInput());
        $this->setValidator("quantite", new sfValidatorNumber(array("required" => false)));

        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setValidator("libelle", new sfValidatorString(array("required" => false)));
      
        $this->setWidget("prix_unitaire", new sfWidgetFormInput());
        $this->setValidator("prix_unitaire", new sfValidatorNumber(array('required' => false)));

        $this->setWidget("montant_ht", new sfWidgetFormInput());
        $this->setValidator("montant_ht", new sfValidatorNumber(array('required' => false)));

        $this->setWidget("taux_tva", new sfWidgetFormInput());
        $this->setValidator("taux_tva", new sfValidatorNumber(array('required' => false)));

        $this->setWidget("montant_tva", new sfWidgetFormInput());
        $this->setValidator("montant_tva", new sfValidatorNumber(array('required' => false)));

        $this->widgetSchema->setNameFormat('facture_edition_ligne_detail[%s]');
        $this->validatorSchema->setPreValidator(new FactureEditionLigneDetailValidator());
    }     

}
