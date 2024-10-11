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

    protected $compteToSave = null;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('adresse', new bsWidgetFormInput());
        $this->setWidget('adresse_complementaire', new bsWidgetFormInput());
        $this->setWidget('code_postal', new bsWidgetFormInput());
        $this->setWidget('commune', new bsWidgetFormInput());
        $this->setWidget('insee', new bsWidgetFormInput());
        $this->setWidget('pays', new bsWidgetFormChoice(array('choices' => self::getCountryList()), array("class" => "select2 form-control")));

        $this->setWidget('droits', new bsWidgetFormChoice(array('choices' => self::getDroits(), 'multiple' => true, 'expanded' => true)));
        $this->setWidget('alternative_logins', new bsWidgetFormInput());

        $this->setWidget('email', new bsWidgetFormInput());
        $this->setWidget('email_teledeclaration', new bsWidgetFormInput());
        $this->setWidget('telephone_perso', new bsWidgetFormInput());
        $this->setWidget('telephone_bureau', new bsWidgetFormInput());
        $this->setWidget('telephone_mobile', new bsWidgetFormInput());
        $this->setWidget('fax', new bsWidgetFormInput());
        $this->setWidget('site_internet', new bsWidgetFormInput());

        $this->widgetSchema->setLabel('adresse', 'N° et nom de rue *');
        $this->widgetSchema->setLabel('adresse_complementaire', 'Adresse complémentaire');
        $this->widgetSchema->setLabel('code_postal', 'CP *');
        $this->widgetSchema->setLabel('insee', 'INSEE');
        $this->widgetSchema->setLabel('commune', 'Ville *');
        $this->widgetSchema->setLabel('pays', 'Pays *');

        $this->widgetSchema->setLabel('droits', 'Droits');
        $this->widgetSchema->setLabel('alternative_logins', 'Logins alternatifs');

        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('email_teledeclaration', 'E-mail de télédéclaration');
        $this->widgetSchema->setLabel('telephone_perso', 'Telephone Perso.');
        $this->widgetSchema->setLabel('telephone_bureau', 'Telephone Bureau');
        $this->widgetSchema->setLabel('telephone_mobile', 'Mobile');
        $this->widgetSchema->setLabel('fax', 'Fax');
        $this->widgetSchema->setLabel('site_internet', 'Site Internet');

        $this->setValidator('adresse', new sfValidatorString(array('required' => false)));
        $this->setValidator('adresse_complementaire', new sfValidatorString(array('required' => false)));
        if ($this->getObject()->getPays() == "FR") {
            $this->setValidator('code_postal', new sfValidatorRegex(array('required' => false, "pattern" => "/^[0-9AB]{5}$/")), array('invalid' => 'Code postal invalide : 5 caractères attendus'));
        }else{
            $this->setValidator('code_postal', new sfValidatorString(array('required' => false)));
        }
        $this->setValidator('insee', new sfValidatorRegex(array('required' => false, "pattern" => "/^[0-9AB]{5}$/")), array('invalid' => 'Code postal invalide : 3 caractères attendus'));
        $this->setValidator('commune', new sfValidatorString(array('required' => false)));
        $this->setValidator('pays', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getCountryList()))));
        $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(self::getDroits()))));
        $this->setValidator('alternative_logins', new sfValidatorString(array('required' => false)));
        $this->setValidator('email', new sfValidatorEmails(array('required' => false), array('invalid' => 'Adresse email invalide.')));
        $this->setValidator('email_teledeclaration', new sfValidatorEmails(array('required' => false), array('invalid' => 'Adresse email invalide.')));
        $this->setValidator('telephone_perso', new sfValidatorRegex(array('required' => false, "pattern" => "/^(\+[1-9][0-9 \.]+|0[0-9 \.]{9,13})$/")), array('invalid' => 'Téléphone invalide : 04 12 34 56 78 ou +33412345678 attendus'));
        $this->setValidator('telephone_bureau', new sfValidatorRegex(array('required' => false, "pattern" => "/^(\+[1-9][0-9 \.]+|0[0-9 \.]{9,13})$/")), array('invalid' => 'Téléphone invalide : 04 12 34 56 78 ou +33412345678 attendus'));
        $this->setValidator('telephone_mobile', new sfValidatorRegex(array('required' => false, "pattern" => "/^(\+[1-9][0-9 \.]+|0[0-9 \.]{9,13})$/")), array('invalid' => 'Téléphone invalide : 04 12 34 56 78 ou +33412345678 attendus'));
        $this->setValidator('fax', new sfValidatorRegex(array('required' => false, "pattern" => "/^(\+[1-9][0-9 \.]+|0[0-9 \.]{9,13})$/")), array('invalid' => 'Fax invalide : 04 12 34 56 78 ou +33412345678 attendus'));
        $this->setValidator('site_internet', new sfValidatorRegex(array('required' => false, "pattern" => "/(^https?:\/\/|^www\.)/")), array('invalid' => 'Site invalide : doit commencer par http://'));

        foreach($this->getExtrasEditables() as $k => $e) {
            $this->setWidget('extra_'.$k, new bsWidgetFormInput());
            $this->widgetSchema->setLabel('extra_'.$k, $e['nom']);
            $this->setValidator('extra_'.$k, new sfValidatorString(array('required' => false)));
        }
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->setDefault('adresse', $this->getObject()->getAdresse());
        $this->setDefault('code_postal', $this->getObject()->getCodePostal());
        $this->setDefault('commune', $this->getObject()->getCommune());
        $this->setDefault('insee', $this->getObject()->getInsee());
        $this->setDefault('pays', $this->getObject()->getPays());
        $this->setDefault('adresse_complementaire', $this->getObject()->getAdresseComplementaire());

        $this->setDefault('email', $this->getObject()->getEmail());
        $this->setDefault('email_teledeclaration', $this->getObject()->getEmailTeledeclaration());
        $this->setDefault('telephone_perso', $this->getObject()->getTelephonePerso());
        $this->setDefault('telephone_bureau', $this->getObject()->getTelephoneBureau());
        $this->setDefault('telephone_mobile', $this->getObject()->getTelephoneMobile());
        $this->setDefault('fax', $this->getObject()->getFax());
        $this->setDefault('site_internet', $this->getObject()->getSiteInternet());

        if($this->getObject()->isNew()){
            $this->setDefault('adresse', $this->getObject()->getSociete()->getAdresse());
            $this->setDefault('code_postal', $this->getObject()->getSociete()->getCodePostal());
            $this->setDefault('commune', $this->getObject()->getSociete()->getCommune());
            $this->setDefault('insee', $this->getObject()->getSociete()->getInsee());
            $this->setDefault('pays', $this->getObject()->getSociete()->getPays());
            $this->setDefault('adresse_complementaire', $this->getObject()->getSociete()->getAdresseComplementaire());

            $this->setDefault('email', $this->getObject()->getSociete()->getEmail());
            $this->setDefault('email_teledeclaration', $this->getObject()->getSociete()->getCompte()->getEmailTeledeclaration());
            $this->setDefault('telephone_perso', $this->getObject()->getSociete()->getTelephonePerso());
            $this->setDefault('telephone_bureau', $this->getObject()->getSociete()->getTelephoneBureau());
            $this->setDefault('telephone_mobile', $this->getObject()->getSociete()->getTelephoneMobile());
            $this->setDefault('fax', $this->getObject()->getSociete()->getFax());
            $this->setDefault('site_internet', $this->getObject()->getSociete()->getSiteInternet());
        }

        $compte = $this->getObject()->getMasterCompte();
        foreach($this->getExtrasEditables(true) as $k => $e) {
            if ($compte->exist('extras')) {
                $this->setDefault('extra_'.$k, $e['value']);
            }
        }

        if(count($compte->getDroits())) {
            $this->setDefault('droits', $compte->getDroits()->toArray(true, false));
        }

        if ($compte->exist('alternative_logins')) {
            $this->setDefault('alternative_logins', join(',', $compte->alternative_logins->toArray()));
        }

    }

    public function getExtrasEditables() {
        $compte = $this->getObject()->getMasterCompte();
        if (!$compte) {
            return array();
        }

        if($compte->type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
            return array();
        }

        return $compte->getExtrasEditables(true);
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $this->getObject()->setAdresse($values['adresse']);
        $this->getObject()->setCommune($values['commune']);
        $this->getObject()->setInsee($values['insee']);
        $this->getObject()->setPays($values['pays']);
        $this->getObject()->setAdresseComplementaire($values['adresse_complementaire']);
        $this->getObject()->setCodePostal($values['code_postal']);

        $this->getObject()->setEmail($values['email']);
        $this->getObject()->getSociete()->getCompte()->setEmailTeledeclaration($values['email']);
        $this->getObject()->setTelephonePerso($values['telephone_perso']);
        $this->getObject()->setTelephoneBureau($values['telephone_bureau']);
        $this->getObject()->setTelephoneMobile($values['telephone_mobile']);
        $this->getObject()->setFax($values['fax']);
        $this->getObject()->setSiteInternet($values['site_internet']);

        $compte = $this->getObject();
        if (get_class($compte) != "Compte" ) {
            $compte = $this->getObject()->getMasterCompte();
        }
        if(!$compte) {
            return;
        }
        foreach($this->getExtrasEditables() as $k => $e) {
            $compte->add('extras')->add($k, $values['extra_'.$k]);
        }
        $compte->remove("droits");
        if(isset($values['droits'])) {
            $compte->add('droits');
            foreach ($values['droits'] as $key => $droit) {
              $compte->getOrAdd("droits")->add(null, $droit);
            }
        }

        $compte->remove('alternative_logins');
        if(isset($values['alternative_logins']) && $values['alternative_logins']){
            $compte->add('alternative_logins', explode(',', $values['alternative_logins']));
        }

        $compte->updateCoordonneesLongLat();

        $this->compteToSave = $compte;
      }

    protected function doSave($con = null) {
        parent::doSave($con);

        if($this->compteToSave) {
            if($this->getObject() instanceof Societe) {
                $this->compteToSave->setSociete($this->getObject());
            }
            $this->compteToSave->save();
        }
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

    public function getDroits() {
        $droits = SocieteConfiguration::getInstance()->getDroits();

        if($this->getObject() instanceof Compte) {
            $compte = $this->getObject();
        } else {
            $compte = $this->getObject()->getMasterCompte();
        }

        if(!$compte->exist('droits')) {

            return $droits;
        }

        foreach($compte->droits as $key) {
            if(isset($droits[$key])) {
                continue;
            }
            $droits[$key] = $key;
        }

        return $droits;
    }

}
