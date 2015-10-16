<?php

class FactureEditionLigneDetailForm extends acCouchdbObjectForm {

    private $sans_categories = false;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if (array_key_exists('sans_categories', $options)) {
            $this->sans_categories = $options['sans_categories'];
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
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
        
        $this->setWidget("identifiant_analytique", new sfWidgetFormInput());
        $this->setValidator("identifiant_analytique", new sfValidatorString(array('required' => false)));
        
        if ($this->sans_categories) {
            $this->setWidget("taux_tva", new sfWidgetFormInputHidden());
        }
        
        $this->setWidget("montant_tva", new sfWidgetFormInput());
        $this->setValidator("montant_tva", new sfValidatorNumber(array('required' => false)));

        $this->widgetSchema->setNameFormat('facture_edition_ligne_detail[%s]');
        
        $this->validatorSchema->setPreValidator(new FactureEditionLigneDetailValidator());
    }
    
     protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if($this->sans_categories) {
            $this->setDefault("taux_tva", 0.2);
        }

    }

}
