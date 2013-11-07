<?php
class AnnuaireAjoutCommercialForm extends acCouchdbObjectForm 
{       
	public function configure()
    {
        $this->setWidgets(array(
        	'identite' => new sfWidgetFormInputText(),
        	'email' => new sfWidgetFormInputText(),
    	));
        $this->widgetSchema->setLabels(array(
        	'identite' => 'Identité*:',
        	'email' => 'Email:'
        ));
        $this->setValidators(array(
        	'identite' => new sfValidatorString(array('required' => true)),
        	'email' => new sfValidatorEmail(array('required' => false))
        ));
  		$this->widgetSchema->setNameFormat('annuaire_ajout_commercial[%s]');
    }
    
    public function doUpdateObject($values) 
    {
        $this->getObject()->get('commerciaux')->add($values['identite'], $values['email']);
    }
}