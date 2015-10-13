<?php

class FactureEditionLigneForm extends acCouchdbObjectForm {

    public function configure()
    {

        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setValidator("libelle", new sfValidatorString(array("required" => false)));

        $this->setWidget("produit_identifiant_analytique", new sfWidgetFormInput());
        $this->setValidator("produit_identifiant_analytique", new sfValidatorString(array('required' => false)));

        $this->setWidget("montant_tva", new sfWidgetFormInputFloat());
        $this->setValidator("montant_tva", new sfValidatorNumber(array('required' => false)));

        $this->setWidget("montant_ht", new sfWidgetFormInputFloat());
        $this->setValidator("montant_ht", new sfValidatorNumber(array('required' => false)));

        $this->getObject()->details->add();
        $this->embedForm('details', new FactureEditionLigneDetailsForm($this->getObject()->details));

        $this->validatorSchema->setPreValidator(new FactureEditionLigneValidator());

        $this->widgetSchema->setNameFormat('facture_edition_ligne[%s]');
    }     

}
