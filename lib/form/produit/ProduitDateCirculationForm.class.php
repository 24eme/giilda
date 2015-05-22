<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProduitDateCirculationForm
 *
 * @author mathurin
 */
class ProduitDateCirculationForm extends sfForm {

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
        $this->initDefaultDates($options['date_circulation']);
    }

    public function configure() {
        $this->setWidgets(array(
            'date_debut' => new sfWidgetFormInputText(),
            'date_fin' => new sfWidgetFormInputText(),
            'campagne' => new sfWidgetFormChoice(array('choices' => $this->getCampagnes()))
        ));
        $this->widgetSchema->setLabels(array(
            'date_debut' => 'Date de début : ',
            'date_fin' => 'Date de fin de circulation : ',
            'campagne' => 'Campagne : '
        ));


        $this->setValidators(array(
            'date_debut' => new sfValidatorString(array('required' => false)),
            'date_fin' => new sfValidatorString(array('required' => false)),
            'campagne' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCampagnes())))
        ));
//        if ($date_circulation = $this->getOption('date_circulation')) {
//            if ($date_circulation->exist('date_debut')) {
//                $date_circulation_debut = new DateTime($date_circulation->date_debut);
//                $date_circulation_campagne = new DateTime($date_circulation->campagne);
//                if ($date_circulation->exist('date_fin')) {
//                    $date_circulation_fin = new DateTime($date_circulation->date_fin);
//                }
//                $this->setDefaults(array(
//                    'date_debut' => $date_circulation_debut->format('d/m/Y'),
//                    'campagne' => $date_circulation_campagne
//                ));
//                if ($date_circulation->exist('date_fin')) {
//                    $this->setDefault('date_fin', $date_circulation_fin->format('d/m/Y'));
//                }
//            }
//        }

        $this->widgetSchema->setNameFormat('produit_date_circulation[%s]');
    }

    private function getCampagnes() {
        $campagneManager = new CampagneManager('08-01');
        $campagneNext = substr($campagneManager->getCurrent(), 5, 4);
        $campagnes = array('' => '');
        for ($campagne = '2013'; $campagne <= $campagneNext; $campagne++) {
            $campagnes[$campagne] = $campagne;
        }
        return $campagnes;
    }

    public function initDefaultDates($date_circulation) {
        $defaults = array();
        if ($date_circulation->exist('date_debut') && $date_circulation->date_debut) {
            $dateDebutArr = explode('-', $date_circulation->date_debut);
            $defaults['date_debut'] = $dateDebutArr[2] . '/' . $dateDebutArr[1] . '/' . $dateDebutArr[0];
        }
        if ($date_circulation->exist('date_fin') && $date_circulation->date_fin) {
            $dateFinArr = explode('-', $date_circulation->date_fin);

            $defaults['date_fin'] = $dateFinArr[2] . '/' . $dateFinArr[1] . '/' . $dateFinArr[0];
        }
        if ($date_circulation->exist('campagne') && $date_circulation->campagne) {

            $defaults['campagne'] = $date_circulation->campagne;
        }

        $this->setDefaults($defaults);
    }

}
