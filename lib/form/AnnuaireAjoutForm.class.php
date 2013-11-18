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
        	'identifiant' => new sfWidgetFormInputText()
    	));
        $this->widgetSchema->setLabels(array(
        	'type' => 'Type*:',
        	'identifiant' => 'CVI*:'
        ));
        $this->setValidators(array(
        	'type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypes()))),
        	'identifiant' => new sfValidatorString(array('required' => true))
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
    	$tiers = $values['tiers'];
    	$libelle = ($tiers->intitule)? $tiers->intitule.' '.$tiers->nom : $tiers->nom;
        $entree = $this->getObject()->get($values['type'])->add($tiers->_id, $libelle);
    }
    
    public function getTiers()
    {
    	$values = $this->getValues();
    	if (isset($values['tiers'])) {
    		return $values['tiers'];
    	}
    	return null;
    }
}