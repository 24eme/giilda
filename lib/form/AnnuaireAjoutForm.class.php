<?php
class AnnuaireAjoutForm extends acCouchdbObjectForm 
{    
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
        $entree = $this->getObject()->get($values['type'])->add($tiers->_id, $tiers->nom);
    }
}