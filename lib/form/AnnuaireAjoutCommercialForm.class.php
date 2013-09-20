<?php
class AnnuaireAjoutCommercialForm extends acCouchdbObjectForm 
{       
	public function configure()
    {
        $this->setWidgets(array(
        	'identite' => new sfWidgetFormInputText()
    	));
        $this->widgetSchema->setLabels(array(
        	'identite' => 'Identité*:'
        ));
        $this->setValidators(array(
        	'identite' => new sfValidatorString(array('required' => true))
        ));
  		$this->widgetSchema->setNameFormat('annuaire_ajout_commercial[%s]');
    }
    
    public function doUpdateObject($values) 
    {
        $this->getObject()->get('commerciaux')->add(null, $values['identite']);
    }
}