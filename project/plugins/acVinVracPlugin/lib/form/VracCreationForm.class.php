<?php

class VracCreationForm extends BaseForm 
{

    public function configure() 
    {
        $this->setWidget('annee', new bsWidgetFormInput());
        $this->setWidget('bordereau', new bsWidgetFormInput());
        $dateRegexpOptions = array('required' => true, 'pattern' => "/^[0-9]{4}$/");
        $dateRegexpErrors = array('required' => 'Champ obligatoire', 'invalid' => 'Année invalide (le format doit être aaaa)');
        $this->setValidators(array(
        	'annee' => new sfValidatorRegex($dateRegexpOptions, $dateRegexpErrors),
            'bordereau' => new sfValidatorString(array('required' => true))
        ));
        $this->widgetSchema->setNameFormat('vrac_creation[%s]');
    }
    
    public function getIdVrac()
    {
    	if ($values = $this->getValues()) {
    		return VracClient::getInstance()->buildNumeroContrat($values['annee'], 0, $values['bordereau']);
    	}
    	return null;
    }

}
