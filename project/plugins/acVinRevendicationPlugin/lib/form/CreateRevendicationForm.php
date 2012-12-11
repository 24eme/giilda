<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CreateRevendicationForm
 * @author mathurin
 */
class CreateRevendicationForm extends BaseForm {

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
                    'default' => array_keys($this->getOdgs()))));
        $this->setWidget('campagne', new sfWidgetFormChoice(array('choices' => $this->getCampagnes())));

        $this->setValidator('odg', new sfValidatorChoice(array('choices' => array_keys($this->getOdgs()))));
        $this->setValidator('campagne', new sfValidatorChoice(array('choices' => array_keys($this->getCampagnes()))));
        $this->widgetSchema->setLabel('odg', 'Sélectionner une ODG :');
        $this->widgetSchema->setLabel('campagne', "Choisir la campagne :");
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
