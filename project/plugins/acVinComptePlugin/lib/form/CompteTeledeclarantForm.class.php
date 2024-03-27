<?php

class CompteTeledeclarantForm extends acCouchdbForm {
    protected $defaultEmail;
    protected $updatedValues;

    public function __construct($doc, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->updatedValues = array();
        $societe = $doc->getSociete();
        $etablissementPrincipal = null;
        if($societe->isTransaction()){
            $etablissementPrincipal = $societe->getEtablissementPrincipal();
        }
        $defaultEmail = null;
        if($etablissementPrincipal){
            $defaultEmail = $etablissementPrincipal->getTeledeclarationEmail();
        }else{
            $defaultEmail = $societe->getTeledeclarationEmail();
        }
        if(!$defaultEmail){
            $defaultEmail = $societe->email;
        }

        $defaults['email'] = $defaultEmail;
        $this->defaultEmail = $defaultEmail;
        if($etablissementPrincipal && $doc->telephone_mobile){
        	$defaults['telephone_mobile'] = $etablissementPrincipal->telephone_mobile;
        } else {
            $defaults['telephone_mobile'] = $societe->telephone_mobile;
        }

        if($etablissementPrincipal && $doc->telephone_bureau){
        	$defaults['telephone_bureau'] = $etablissementPrincipal->telephone_bureau;
        } else {
            $defaults['telephone_bureau'] = $societe->telephone_bureau;
        }

        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function getUpdatedValues()
    {
        return $this->updatedValues;
    }

    public function hasUpdatedValues()
    {
        return (count($this->updatedValues) > 0);
    }

    public function configure() {
        $this->setWidgets(array(
            'email' => new bsWidgetFormInput(),
            'telephone_bureau' => new bsWidgetFormInput(),
            'telephone_mobile' => new bsWidgetFormInput(),
            'mdp1' => new bsWidgetFormInputPassword(),
            'mdp2' => new bsWidgetFormInputPassword()
        ));

        $this->widgetSchema->setLabels(array(
            'email' => 'Adresse e-mail : ',
            'telephone_bureau' => 'Téléphone bureau :',
            'telephone_mobile' => 'Téléphone mobile :',
            'mdp1' => 'Mot de passe : ',
            'mdp2' => 'Vérification du mot de passe : '
        ));

        $this->widgetSchema->setNameFormat('ac_vin_compte[%s]');

        $this->setValidator('email', new sfValidatorEmail(array('required' => true), array('required' => 'Champ obligatoire', 'invalid' => 'Adresse email invalide.')));
        $mdpValidator = new sfValidatorRegex(array('required' => false,
            'pattern' => "/^[^éèçùà€£µôûîêâöüïë§]+$/i",
            'min_length' => 8), array('required' => 'Le mot de passe est obligatoire',
            'invalid' => 'Le mot de passe doit être constitué de caractères non accentués',
            'min_length' => 'Le mot de passe doit être constitué de 8 caractères min.'));

        $this->setValidator('mdp1', $mdpValidator);
        $this->setValidator('mdp2', $mdpValidator);

        $this->setValidator('telephone_bureau', new sfValidatorRegex(array('required' => false,
            'pattern' => "/^\+?[0-9 \.]{10,14}$/",
            'min_length' => 10,
            'max_length' => 14),
            array(
            'invalid' => "Le numéro de téléphone doit être de la forme 0412345678 ou +33412345678")));

        $this->setValidator('telephone_mobile', new sfValidatorRegex(array('required' => false,
                'pattern' => "/^\+?[0-9 \.]{10,14}$/"),
                array(
                'invalid' => "Le numéro de téléphone doit être de la forme 0412345678 ou +33412345678",
        )));

        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', sfValidatorSchemaCompare::EQUAL, 'mdp2', array(), array('invalid' => 'Les mots de passe doivent être identique.')));
    }

    public function save() {
        $societe = $this->getDocument()->getSociete();

        if (!$this->isValid())
        {
          throw $this->getErrorSchema();
        }

        if ($this->getValue('mdp1')) {
            $this->getDocument()->setMotDePasseSSHA($this->getValue('mdp1'));
        }

        $this->getDocument()->add('teledeclaration_active', true);
        $this->getDocument()->save();

        $email = $this->getValue('email');

        if ($this->defaultEmail != $email) {
        	$this->updatedValues['email'] = array($this->defaultEmail, $email);
        }

        if(!$email) {
            return;
        }

        SocieteClient::getInstance()->clearSingleton();
        $societe = SocieteClient::getInstance()->find($this->getDocument()->id_societe);

        if ($societe->isTransaction()) {
            $allEtablissements = $societe->getEtablissementsObj();
            foreach ($allEtablissements as $etablissementObj) {
                $etb = $etablissementObj->etablissement;
                if ((!$etb->exist('email') || !$etb->email) && !$etb->isSameContactThanSociete()) {
                    $etb->email = $email;
                }
                $etb->add('teledeclaration_email', $email);
                $etb->save();
            }
        }

        if (!$societe->isTransaction()) {
            $societe->add('teledeclaration_email', $email);
            $societe->save();
        }

        if(!$societe->email) {
            $societe->email = $email;
            $societe->save();
        }

        $documentToUpdate = $societe;
        if($societe->isTransaction()){
            $documentToUpdate = $societe->getEtablissementPrincipal();
        }

        if ($tel = $this->getValue('telephone_bureau')) {
            if($documentToUpdate->telephone_bureau != $tel) {
        	    $this->updatedValues['telephone_bureau'] = array($documentToUpdate->telephone_bureau, $tel);
            }
            $documentToUpdate->telephone_bureau = $tel;
            $documentToUpdate->save();
        }

        if ($mobile = $this->getValue('telephone_mobile')) {
            if($documentToUpdate->telephone_mobile != $mobile) {
        	      $this->updatedValues['telephone_mobile'] = array($documentToUpdate->telephone_mobile, $mobile);
            }
            $documentToUpdate->telephone_mobile = $mobile;
            $documentToUpdate->save();
        }
    }
}
