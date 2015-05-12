<?php

class CompteTeledeclarantCreationForm extends CompteTeledeclarantForm {

    private $typeCompte;

    public function __construct($object, $options = array(), $CSRFSecret = null) {        
        $this->typeCompte = $object->getSociete()->type_societe;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->getValidator('mdp1')->setOption('required', true);
        $this->getValidator('mdp2')->setOption('required', true);
        if ($this->typeCompte == SocieteClient::SUB_TYPE_COURTIER) {
            $this->setWidget('carte_pro', new sfWidgetFormInputText());
            $this->getWidget('carte_pro')->setLabel("Numéro de carte professionnelle :");
            $this->setValidator('carte_pro', new sfValidatorString(array('required' => false)));
        }

        if ($this->typeCompte == SocieteClient::SUB_TYPE_VITICULTEUR || $this->typeCompte == SocieteClient::SUB_TYPE_NEGOCIANT) {
            $this->setWidget('siret', new sfWidgetFormInputText());
            $this->getWidget('siret')->setLabel("Numéro de SIRET :");
            $this->setValidator('siret', new sfValidatorRegex(array('required' => false,
                'pattern' => "/^[0-9]{14}$/",
                'min_length' => 14,
                'max_length' => 14), array('required' => 'Le numéro de SIRET est obligatoire',
                'invalid' => 'Le numéro de SIRET doit être constitué de 14 chiffres',
                'min_length' => 'Le numéro de SIRET doit être constitué de 14 chiffres',
                'max_length' => 'Le numéro de SIRET doit être constitué de 14 chiffres')));

            $this->setWidget('num_accises', new sfWidgetFormInputText());
            $this->getWidget('num_accises')->setLabel("Numéro d'ACCISE :");
            $this->setValidator('num_accises', new sfValidatorRegex(array('required' => false,
                'pattern' => "/^[0-9A-Za-z]{13}$/",
                'min_length' => 13,
                'max_length' => 13), array('required' => "Le numéro d'ACCISE est obligatoire",
                'invalid' => "Le numéro d'ACCISE doit être constitué de 13 caractères alphanumériques",
                'min_length' => "Le numéro d'ACCISE doit être constitué de 13 caractères alphanumériques",
                'max_length' => "Le numéro d'ACCISE doit être constitué de 13 caractères alphanumériques")));
        }
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $etbPrincipal = $this->getObject()->getSociete()->getEtablissementPrincipal();
        if (($this->typeCompte == SocieteClient::SUB_TYPE_COURTIER) && ($this->getValue('carte_pro'))) {
            $etbPrincipal->carte_pro = $this->getValue('carte_pro');
            $etbPrincipal->save();
        }
        if ((($this->typeCompte == SocieteClient::SUB_TYPE_NEGOCIANT) || ($this->typeCompte == SocieteClient::SUB_TYPE_VITICULTEUR)) && ($this->getValue('num_accises'))) {
            $etbPrincipal->no_accises = strtoupper($this->getValue('num_accises'));
            $etbPrincipal->save();
        }        
    }

    public function getTypeCompte() {
        if (!$this->typeCompte) {
            $this->typeCompte = $this->getObject()->type_societe;
        }
        return $this->typeCompte;
    }

}
