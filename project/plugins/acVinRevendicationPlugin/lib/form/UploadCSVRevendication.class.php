<?php

class UploadCSVRevendicationForm extends UploadCSVForm {

    private $anneeCampagneStart = 1991;

    /**
     * 
     */
    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->setWidget('odg', new sfWidgetFormChoice(array('choices' => $this->getOdgs(),
                    'multiple' => false,
                    'expanded' => false,
                    'default' => array_keys($this->getOdgs()))));
        $this->setWidget('campagne', new sfWidgetFormChoice(array('choices' => $this->getCampagnes(),
                    'multiple' => false,
                    'expanded' => false)));

        $this->setValidator('odg', new sfValidatorChoice(array('choices' => array_keys($this->getOdgs()), 'multiple' => true)));
        $this->setValidator('campagne', new sfValidatorChoice(array('choices' => array_keys($this->getCampagnes()), 'multiple' => true)));
        $this->widgetSchema->setLabel('odg', 'Sélectionner une ODG :');
        $this->widgetSchema->setLabel('campagne', "Choisir la campagne :");
        $this->widgetSchema->setLabel('file', "Choisir un fichier :");


        $this->widgetSchema->setNameFormat('csvRevendication[%s]');
    }

    public function getOdgs() {
        return array('tours' => 'Tours');
    }

    public function getCampagnes() {
        $annee = date('Y');

        $campagnes = array();
        for ($currentA = $annee; $currentA > $this->anneeCampagneStart; $currentA--) {
            $key = $currentA . ($currentA + 1);
            $value = $currentA . '-' . ($currentA + 1);
            $campagnes[$key] = $value;
        }
        return $campagnes;
    }

}
