<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteExtendedModificationForm
 * @author mathurin
 */
class CompteForm extends acCouchdbObjectForm {

    private $compte;
    private $societeCompte;

    public function __construct(Compte $compte, $options = array(), $CSRFSecret = null) {
        $this->compte = $compte;
        $this->societeCompte = $this->compte->getSociete()->getMasterCompte();
        parent::__construct($compte, $options, $CSRFSecret);
        if ($this->compte->isSocieteContact()) {
            $this->defaults['pays'] = 'FR';
        }
    }

    public function configure() {
        parent::configure();
        $this->setWidget('statut', new bsWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false, 'expanded' => true)));
        $this->setWidget('civilite', new bsWidgetFormChoice(array('choices' => $this->getCiviliteList())));
        $this->setWidget('nom', new bsWidgetFormInput());
        $this->setWidget('prenom', new bsWidgetFormInput());
        $this->setWidget('fonction', new bsWidgetFormInput());
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->setWidget('adresse', new bsWidgetFormInput());
        $this->setWidget('adresse_complementaire', new bsWidgetFormInput());
        $this->setWidget('code_postal', new bsWidgetFormInput());
        $this->setWidget('commune', new bsWidgetFormInput());
        $this->setWidget('cedex', new bsWidgetFormInput());
        $this->setWidget('pays', new bsWidgetFormChoice(array('choices' => $this->getCountryList()), array("class" => "select2 form-control")));
        $this->setWidget('droits', new bsWidgetFormChoice(array('choices' => $this->getDroits(), 'multiple' => true, 'expanded' => true)));

        $this->setWidget('email', new bsWidgetFormInput());
        $this->setWidget('telephone_perso', new bsWidgetFormInput());
        $this->setWidget('telephone_bureau', new bsWidgetFormInput());
        $this->setWidget('telephone_mobile', new bsWidgetFormInput());
        $this->setWidget('fax', new bsWidgetFormInput());
        $this->setWidget('site_internet', new bsWidgetFormInput());

        $this->widgetSchema->setLabel('statut', 'Statut *');
        $this->widgetSchema->setLabel('civilite', 'Civilite *');
        $this->widgetSchema->setLabel('nom', 'Nom *');
        $this->widgetSchema->setLabel('prenom', 'Prenom');
        $this->widgetSchema->setLabel('fonction', 'Fonction *');
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');

        $this->widgetSchema->setLabel('adresse', 'N° et nom de rue *');
        $this->widgetSchema->setLabel('adresse_complementaire', 'Adresse complémentaire');
        $this->widgetSchema->setLabel('code_postal', 'CP *');
        $this->widgetSchema->setLabel('commune', 'Ville *');
        $this->widgetSchema->setLabel('cedex', 'Cedex');
        $this->widgetSchema->setLabel('pays', 'Pays *');
        $this->widgetSchema->setLabel('droits', 'Droits *');

        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('telephone_perso', 'Telephone Perso.');
        $this->widgetSchema->setLabel('telephone_bureau', 'Telephone Bureau');
        $this->widgetSchema->setLabel('telephone_mobile', 'Mobile');
        $this->widgetSchema->setLabel('fax', 'Fax');
        $this->widgetSchema->setLabel('site_internet', 'Site Internet');

        $this->setValidator('statut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
        $this->setValidator('civilite', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCiviliteList()))));
        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('prenom', new sfValidatorString(array('required' => false)));
        $this->setValidator('fonction', new sfValidatorString(array('required' => true)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
        $this->setValidator('adresse', new sfValidatorString(array('required' => false)));
        $this->setValidator('adresse_complementaire', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_postal', new sfValidatorString(array('required' => false)));
        $this->setValidator('commune', new sfValidatorString(array('required' => false)));
        $this->setValidator('cedex', new sfValidatorString(array('required' => false)));
        $this->setValidator('pays', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCountryList()))));
        $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($this->getDroits()))));
        $this->setValidator('email', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_perso', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_bureau', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_mobile', new sfValidatorString(array('required' => false)));
        $this->setValidator('fax', new sfValidatorString(array('required' => false)));
        $this->setValidator('site_internet', new sfValidatorString(array('required' => false)));

        if ($this->compte->isNew()) {
            $this->widgetSchema['statut']->setAttribute('disabled', 'disabled');
        }


        $this->widgetSchema->setNameFormat('compte_modification[%s]');
    }

    public function getCountryList() {
        $destinationChoicesWidget = new bsWidgetFormI18nChoiceCountry(array('culture' => 'fr', 'add_empty' => true));
        $destinationChoices = $destinationChoicesWidget->getChoices();
        $destinationChoices['inconnu'] = 'Inconnu';
        return $destinationChoices;
    }

    public function getAllTags() {
        return CompteClient::getInstance()->getAllTags();
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        if ($this->getObject()->isNew()) {
            $this->setDefault('statut', $this->getObject()->getSociete()->statut);
        }
        if (!$this->compte->isSocieteContact()) {
            if ($this->compte->adresse == $this->societeCompte->adresse) {
                $this->setDefault('adresse', '');
            }
//                var_dump($this->compte->adresse_complementaire,$this->societeCompte->adresse_complementaire); exit;
            if ($this->compte->adresse_complementaire == $this->societeCompte->adresse_complementaire) {
                $this->setDefault('adresse_complementaire', '');
            }
            if ($this->compte->code_postal == $this->societeCompte->code_postal) {
                $this->setDefault('code_postal', '');
            }

            if ($this->compte->commune == $this->societeCompte->commune) {
                $this->setDefault('commune', '');
            }

            if ($this->compte->cedex == $this->societeCompte->cedex) {
                $this->setDefault('cedex', '');
            }
            if ($this->compte->pays == $this->societeCompte->pays) {
                $this->setDefault('pays', '');
            }
        }
    }

    protected function doSave($con = null) {
        parent::dosave();
//        if()
//        var_dump($this->values['adresse_societe']); exit;
        //$this->getObject()->doSameCoordonneeThanSocieteAndSave();
        $this->getObject()->getCouchdbDocument()->save();
    }

    public function getCiviliteList() {
        return array('Mme' => 'Mme', 'M' => 'M');
    }

    public function getStatuts() {
        return EtablissementClient::getStatuts();
    }

    public function getDroits() {

        return array(Roles::CONTRAT => "Contrat");
    }

}
