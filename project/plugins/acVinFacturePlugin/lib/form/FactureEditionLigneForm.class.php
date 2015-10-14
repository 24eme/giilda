<?php

class FactureEditionLigneForm extends acCouchdbObjectForm {

    private $sans_categories = false;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if (array_key_exists('sans_categories', $options)) {
            $this->sans_categories = $options['sans_categories'];
        }
        parent::__construct($object, $options, $CSRFSecret);
        
    }

    public function configure() {
        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget("produit_identifiant_analytique", new sfWidgetFormInput());
        $this->setWidget("montant_tva", new sfWidgetFormInputFloat());
        $this->setWidget("montant_ht", new sfWidgetFormInputFloat());

        if ($this->sans_categories) {
            $this->setWidget("libelle", new sfWidgetFormInputHidden());
            $this->setWidget("montant_tva", new sfWidgetFormInputHidden());
            $this->setWidget("montant_ht", new sfWidgetFormInputHidden());
            
        }

        $this->setValidator("libelle", new sfValidatorString(array("required" => false)));
        $this->setValidator("produit_identifiant_analytique", new sfValidatorString(array('required' => false)));
        $this->setValidator("montant_tva", new sfValidatorNumber(array('required' => false)));
        $this->setValidator("montant_ht", new sfValidatorNumber(array('required' => false)));

        $this->getObject()->details->add();
        $this->embedForm('details', new FactureEditionLigneDetailsForm($this->getObject()->details,array('sans_categories' => $this->sans_categories)));

        $this->validatorSchema->setPreValidator(new FactureEditionLigneValidator());

        $this->widgetSchema->setNameFormat('facture_edition_ligne[%s]');
    }
    
     protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if($this->sans_categories) {
            $this->setDefault("libelle", "Vierge");
        }

    }

}
