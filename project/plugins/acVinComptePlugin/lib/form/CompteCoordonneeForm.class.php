<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteSocieteModificationForm
 * @author mathurin
 */
class CompteCoordonneeForm extends acCouchdbObjectForm {

    private $compte;
    private $reduct_rights = false;

    public function __construct(Compte $compte, $reduct_rights = false, $options = array(), $CSRFSecret = null) {
        $this->compte = $compte;
        $this->reduct_rights = $reduct_rights;
        parent::__construct($compte, $options, $CSRFSecret);
        $this->defaults['pays'] = 'FR';
    }

    public function configure() {
        parent::configure();
        if (!$this->reduct_rights) {
            $this->setWidget('adresse', new bsWidgetFormInput());
            $this->setWidget('adresse_complementaire', new bsWidgetFormInput());
            $this->setWidget('code_postal', new bsWidgetFormInput());
            $this->setWidget('commune', new bsWidgetFormInput());
            $this->setWidget('pays', new bsWidgetFormChoice(array('choices' => $this->getCountryList()), array("class"=>"select2 form-control")));
            $this->setWidget('droits', new bsWidgetFormChoice(array('choices' => $this->getDroits(), 'multiple' => true, 'expanded' => true)));
        }

        $this->setWidget('email', new bsWidgetFormInput());
        $this->setWidget('telephone_perso', new bsWidgetFormInput());
        $this->setWidget('telephone_bureau', new bsWidgetFormInput());
        $this->setWidget('telephone_mobile', new bsWidgetFormInput());
        $this->setWidget('fax', new bsWidgetFormInput());
        $this->setWidget('site_internet', new bsWidgetFormInput());

        //   $this->setWidget('tags', new sfWidgetFormChoice(array('choices' => $this->getAllTags())));
        if (!$this->reduct_rights) {
            $this->widgetSchema->setLabel('adresse', 'N° et nom de rue *');
            $this->widgetSchema->setLabel('adresse_complementaire', 'Adresse complémentaire');
            $this->widgetSchema->setLabel('code_postal', 'CP *');
            $this->widgetSchema->setLabel('commune', 'Ville *');
            $this->widgetSchema->setLabel('pays', 'Pays *');
            $this->widgetSchema->setLabel('droits', 'Droits *');
        }
        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('telephone_perso', 'Telephone Perso.');
        $this->widgetSchema->setLabel('telephone_bureau', 'Telephone Bureau');
        $this->widgetSchema->setLabel('telephone_mobile', 'Mobile');
        $this->widgetSchema->setLabel('fax', 'Fax');
        $this->widgetSchema->setLabel('site_internet', 'Site Internet');

        //    $this->widgetSchema->setLabel('tags', 'Tags');

        if (!$this->reduct_rights) {
            $this->setValidator('adresse', new sfValidatorString(array('required' => true)));
            $this->setValidator('adresse_complementaire', new sfValidatorString(array('required' => false)));
            $this->setValidator('code_postal', new sfValidatorString(array('required' => true)));
            $this->setValidator('commune', new sfValidatorString(array('required' => true)));
            $this->setValidator('pays', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCountryList()))));
            $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($this->getDroits()))));
        }

        $this->setValidator('email', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_perso', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_bureau', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_mobile', new sfValidatorString(array('required' => false)));
        $this->setValidator('fax', new sfValidatorString(array('required' => false)));
        $this->setValidator('site_internet', new sfValidatorString(array('required' => false)));

        //  $this->setValidator('tags', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAllTags()))));


        $this->widgetSchema->setNameFormat('compte_modification[%s]');
    }

    public function getDroits() {
        return Roles::$teledeclarationLibelles;
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

    protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();
        if($this->compte->isNew()){
            $this->compte->statut = CompteClient::STATUT_ACTIF;
        }
        if($this->compte->isSocieteContact())
        {
            $this->compte->statut = $this->compte->getSociete()->statut;
            $this->compte->add('type_societe',$this->compte->getSociete()->type_societe);
            $this->compte->buildDroits();
        }
        if($this->compte->isEtablissementContact()){
            $this->compte->statut = $this->compte->getEtablissement()->statut;
        }
        $this->object->getCouchdbDocument()->save();
    }

}
