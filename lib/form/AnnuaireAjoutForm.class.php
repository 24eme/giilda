<?php
class AnnuaireAjoutForm extends acCouchdbObjectForm 
{    
	protected $tiers;
	
	public function __construct(acCouchdbJson $object, $tiers = null, $options = array(), $CSRFSecret = null) 
	{
		$this->tiers = $tiers;
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
        if ($this->tiers) {
        	$this->setDefaults(array(
        		'type' => $this->getType($this->tiers->type),
    			'identifiant' => $this->tiers->cvi
    		));
        }
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
        $entree = $this->getObject()->get($values['type'])->add($tiers->_id, $tiers->nom);
    }
    
    public function getTiers()
    {
    	$values = $this->getValues();
    	if (isset($values['tiers'])) {
    		return $values['tiers'];
    	}
    	return null;
    }
    
    protected function getType($tiersType)
    {
    	return AnnuaireClient::getTiersCorrespondanceType($tiersType);
    }
}