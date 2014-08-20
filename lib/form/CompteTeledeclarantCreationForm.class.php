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
            $this->getWidget('siret')->setLabel("Numéro de siret* :");
            $this->setValidator('siret', new sfValidatorRegex(array('required' => true,
                'pattern' => "/^[0-9]{10}$/",
                'min_length' => 14,
                'max_length' => 14), array('required' => 'Le numéro de siret est obligatoire',
                'invalid' => 'Le numéro de siret doit être costitué de 10 chiffres',
                'min_length' => 'Le numéro de siret doit être costitué de 14 chiffres',
                'max_length' => 'Le numéro de siret doit être costitué de 14 chiffres')));
        }
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (($this->typeCompte == SocieteClient::SUB_TYPE_COURTIER) && ($this->getValue('carte_pro'))) {
            $etbPrincipal = $this->getObject()->getSociete()->getEtablissementPrincipal();
            $etbPrincipal->carte_pro = $this->getValue('carte_pro

            ');
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
