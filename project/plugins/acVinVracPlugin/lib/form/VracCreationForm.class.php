<?php

class VracCreationForm extends BaseForm 
{

    public function configure() 
    {
        $this->setWidget('annee', new bsWidgetFormInput());
        $this->setWidget('bordereau', new bsWidgetFormInput());
        $dateRegexpOptions = array('required' => false, 'pattern' => "/^[0-9]{4,5}$/");
        $dateRegexpErrors = array('required' => 'Champ obligatoire', 'invalid' => 'Année invalide (le format doit être aaaa(+x))');
        $this->setValidators(array(
        	'annee' => new sfValidatorRegex($dateRegexpOptions, $dateRegexpErrors),
            'bordereau' => new sfValidatorString(array('required' => false))
        ));
        $this->validatorSchema->setPostValidator(new ValidatorVracCreation());
        $this->widgetSchema->setNameFormat('vrac_creation[%s]');
    }
    
    public function getIdVrac()
    {
    	if ($values = $this->getValues()) {
    		if (!$values['annee']) {
    			$annee = date('Y');
    			$type = date('md');
    		} else {
    			$annee = $values['annee'];
    			$type = 0;
	    		if (preg_match('/^([0-9]{4})([0-9]{1})$/', $annee, $m)) {
	    			$annee = $m[1];
	    			$type = $m[2];
	    		}
    		}
    		$bordereau = ($values['bordereau'])? $values['bordereau'] : null;
    		return VracClient::getInstance()->buildNumeroContrat($annee, $type, 0, $bordereau);
    	}
    	return null;
    }

}
