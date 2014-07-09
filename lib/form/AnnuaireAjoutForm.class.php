<?php
class AnnuaireAjoutForm extends acCouchdbObjectForm 
{       
	protected $type;
	 
	public function __construct(acCouchdbJson $object, $type = null, $options = array(), $CSRFSecret = null) {
		$this->type = $type;
		parent::__construct($object, $options, $CSRFSecret);
	}
	public function configure()
    {
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
        $this->validatorSchema->setPostValidator(new AnnuaireAjoutValidator());
  		$this->widgetSchema->setNameFormat('annuaire_ajout[%s]');
    }
    
    protected function getTypes()
    {
    	return AnnuaireClient::getAnnuaireTypes();
    }
    
    public function doUpdateObject($values) 
    {
    	$tiers = $values['etablissement'];
    	$libelle = ($tiers->nom)? $tiers->nom : $tiers->raison_sociale;
        $entree = $this->getObject()->get($values['type'])->add($tiers->_id, $libelle);
    }
    
    public function getEtablissement()
    {
    	$values = $this->getValues();
    	if (isset($values['etablissement'])) {
    		return $values['etablissement'];
    	}
    	return null;
    }
}