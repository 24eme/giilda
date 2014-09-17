<?php

class CompteTeledeclarantForm extends acCouchdbObjectForm {

    private $oldEmail;

    public function __construct($object, $options = array(), $CSRFSecret = null) {
        $this->oldEmail = $object->email;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'email' => new sfWidgetFormInputText(),
            'mdp1' => new sfWidgetFormInputPassword(),
            'mdp2' => new sfWidgetFormInputPassword()
        ));

        $this->widgetSchema->setLabels(array(
            'email' => 'Adresse e-mail* : ',
            'mdp1' => 'Mot de passe* : ',
            'mdp2' => 'Vérification du mot de passe* : '
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

        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', sfValidatorSchemaCompare::EQUAL, 'mdp2', array(), array('invalid' => 'Les mots de passe doivent être identique.')));
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);

        if ($this->getValue('mdp1')) {
            $this->getObject()->setMotDePasseSSHA($this->getValue('mdp1'));
        }
        $this->getObject()->add('teledeclaration_active', true);
        $this->getObject()->email = $this->oldEmail;
    }
    
    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $societe = $this->getObject()->getSociete();
        
        $defaultEmail = null;
        if($societe->isTransaction()){
            $etablissementPrincipal = $societe->getEtablissementPrincipal();
            $defaultEmail = $etablissementPrincipal->getEmailTeledeclaration();
        }else{
            $defaultEmail = $societe->getEmailTeledeclaration();
        }
        if(!$defaultEmail){
            $defaultEmail = $societe->email;
        }
        $this->setDefault('email', $defaultEmail);
    }

}
