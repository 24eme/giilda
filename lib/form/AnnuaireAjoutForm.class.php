<?php

class AnnuaireAjoutForm extends acCouchdbObjectForm {

    protected $type;
    protected $etablissements;
    protected $societeChoice = false;

    public function __construct(acCouchdbJson $object, $type = null, $etablissements = null, $options = array(), $CSRFSecret = null) {
        $this->type = $type;
        $this->etablissements = $etablissements;
        if ($this->etablissements && (count($this->etablissements) > 1 ) && ($this->type != AnnuaireClient::ANNUAIRE_COMMERICAUX_KEY)) {
            $this->societeChoice = true;
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'type' => new sfWidgetFormChoice(array('choices' => $this->getTypes())),
            'tiers' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
            'type' => 'Type*:',
            'tiers' => 'Identifiant*:'
        ));
        $this->setValidators(array(
            'type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypes()))),
            'tiers' => new sfValidatorString(array('required' => true))
        ));
        if($this->societeChoice){
            $this->setWidget('etablissementChoice', new sfWidgetFormChoice(array('choices' => $this->getEtablissements())));
            $this->setValidator('etablissementChoice', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getEtablissements()))));
            $this->widgetSchema->setLabel('etablissementChoice', 'Etablissement*:');
        }
        $this->validatorSchema->setPostValidator(new AnnuaireAjoutValidator($this->societeChoice));
        $this->widgetSchema->setNameFormat('annuaire_ajout[%s]');
    }

    protected function getTypes() {
        return AnnuaireClient::getAnnuaireTypes();
    }

    public function doUpdateObject($values) 
    {
        if($this->societeChoice){
           $tiers = EtablissementClient::getInstance()->retrieveById($values['etablissementChoice']);
        }else{
            $societe = $values['societe'];
            $tiers = $societe->getEtablissementPrincipal();
        }
    	$libelle = ($tiers->nom)? $tiers->nom : $tiers->raison_sociale;
        $entree = $this->getObject()->get($values['type'])->add($tiers->_id, $libelle);
    }

    public function getSociete() {
        $values = $this->getValues();
        if (isset($values['societe'])) {
            return $values['societe'];
        }
        return null;
    }
    
    public function getEtablissements() {
        $etablissements = array('0' => 'Choisir un établissement');
        foreach ($this->etablissements as $key => $etablissementObj) {
            $etablissements[$etablissementObj->etablissement->identifiant] = $etablissementObj->etablissement->nom; 
        }
        return $etablissements;
    }
    
    public function hasSocieteChoice() {
        return $this->societeChoice;
    }

}
