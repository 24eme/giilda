<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteExtendedModificationForm
 * @author mathurin
 */
class CompteGeneriqueForm extends acCouchdbObjectForm {

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }
            
    public function configure() {
        parent::configure();       

        $this->setWidget('adresse', new bsWidgetFormInput());
        $this->setWidget('adresse_complementaire', new bsWidgetFormInput());
        $this->setWidget('code_postal', new bsWidgetFormInput());
        $this->setWidget('commune', new bsWidgetFormInput());
        $this->setWidget('cedex', new bsWidgetFormInput());
        $this->setWidget('pays', new bsWidgetFormChoice(array('choices' => self::getCountryList()), array("class" => "select2 form-control")));
        $this->setWidget('droits', new bsWidgetFormChoice(array('choices' => self::getDroits(), 'multiple' => true, 'expanded' => true)));

        $this->setWidget('email', new bsWidgetFormInput());
        $this->setWidget('telephone_perso', new bsWidgetFormInput());
        $this->setWidget('telephone_bureau', new bsWidgetFormInput());
        $this->setWidget('telephone_mobile', new bsWidgetFormInput());
        $this->setWidget('fax', new bsWidgetFormInput());
        $this->setWidget('site_internet', new bsWidgetFormInput());

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
        
        $this->setValidator('adresse', new sfValidatorString(array('required' => false)));
        $this->setValidator('adresse_complementaire', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_postal', new sfValidatorString(array('required' => false)));
        $this->setValidator('commune', new sfValidatorString(array('required' => false)));
        $this->setValidator('cedex', new sfValidatorString(array('required' => false)));
        $this->setValidator('pays', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getCountryList()))));
        $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(self::getDroits()))));
        $this->setValidator('email', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_perso', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_bureau', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_mobile', new sfValidatorString(array('required' => false)));
        $this->setValidator('fax', new sfValidatorString(array('required' => false)));
        $this->setValidator('site_internet', new sfValidatorString(array('required' => false)));
        
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        
        $this->setDefault('adresse', $this->getObject()->siege->adresse);
        $this->setDefault('code_postal', $this->getObject()->siege->code_postal);
        $this->setDefault('commune', $this->getObject()->siege->commune);
        $this->setDefault('pays', $this->getObject()->siege->pays);
        $this->setDefault('adresse_complementaire', $this->getObject()->adresse_complementaire);
        $this->setDefault('cedex', $this->getObject()->cedex);

        $this->setDefault('email', $this->getObject()->getEmail());
        $this->setDefault('telephone_perso', $this->getObject()->getTelephonePerso());
        $this->setDefault('telephone_bureau', $this->getObject()->getTelephoneBureau());
        $this->setDefault('telephone_mobile', $this->getObject()->getTelephoneMobile());
        $this->setDefault('fax', $this->getObject()->getFax());
        $this->setDefault('site_internet', $this->getObject()->getSiteInternet());
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $this->getObject()->setAdresse($values['adresse']);
        $this->getObject()->setCommune($values['commune']);
        $this->getObject()->setPays($values['pays']);
        $this->getObject()->setCedex($values['cedex']);
        $this->getObject()->setAdresseComplementaire($values['adresse_complementaire']);
        $this->getObject()->setCodePostal($values['code_postal']);

        $this->getObject()->setEmail($values['email']);
        $this->getObject()->setTelephonePerso($values['telephone_perso']);
        $this->getObject()->setTelephoneBureau($values['telephone_bureau']);
        $this->getObject()->setTelephoneMobile($values['telephone_mobile']);
        $this->getObject()->setFax($values['fax']);
        $this->getObject()->setSiteInternet($values['site_internet']);
    }


    public static function getCountryList() {
        $destinationChoicesWidget = new bsWidgetFormI18nChoiceCountry(array('culture' => 'fr', 'add_empty' => true));
        $destinationChoices = $destinationChoicesWidget->getChoices();
        $destinationChoices['inconnu'] = 'Inconnu';
        return $destinationChoices;
    }
   
    
    public static function getCiviliteList() {
        return array('Mme' => 'Mme', 'M' => 'M');
    }

    public static function getStatuts() {
        return EtablissementClient::getStatuts();
    }

    public static function getDroits() {

        return array(Roles::CONTRAT => "Contrat");
    }

}
