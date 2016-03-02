<?php

abstract class CompteCoordonneeSameSocieteForm extends acCouchdbObjectForm {

    public function configure() {
        parent::configure();

            $this->setWidget('adresse_societe', new bsWidgetFormChoice(array('choices' => $this->getAdresseSocieteChoice(), 'expanded' => true, 'multiple' => false)));
            $this->widgetSchema->setLabel('adresse_societe', 'Même adresse que la société ?');
            $this->setValidator('adresse_societe', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAdresseSocieteChoice()))));
        }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        $this->setDefault('adresse_societe', (int) $this->getObject()->isSameCoordonneeThanSociete());
    }

    public function getAdresseSocieteChoice() {

        return array(1 => 'oui', 0 => 'non');
    }

    public function getAllChampsPerso() {
        return array('' => '',
            'adresse' => 'adresse',
            'adresse_complementaire' => 'adresse complementaire',
            'code_postal' => 'code postal',
            'commune' => 'commune',
            'pays' => 'pays',
            'droits' => 'droits',
            'email' => 'email',
            'telephone_perso' => 'telephone perso',
            'telephone_bureau' => 'telephone bureau',
            'telephone_mobile' => 'telephone mobile',
            'fax' => 'fax',
            'site_internet' => 'site internet'
        );
    }

}
