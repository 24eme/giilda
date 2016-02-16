<?php

class VracCreationForm extends BaseForm 
{

    public function configure() 
    {
        $this->setWidget('annee', new bsWidgetFormInput());
        $this->setWidget('bordereau', new bsWidgetFormInput());
        $dateRegexpOptions = array('required' => false, 'pattern' => "/^[0-9]{4}[a-zA-Z0-9]{0,1}$/");
        $dateRegexpErrors = array('required' => 'Champ obligatoire', 'invalid' => 'Année invalide (le format doit être aaaa(+x))');
        $this->setValidators(array(
        	'annee' => new sfValidatorRegex($dateRegexpOptions, $dateRegexpErrors),
            'bordereau' => new sfValidatorString(array('required' => false))
        ));
        $this->widgetSchema->setNameFormat('vrac_creation[%s]');
    }
    
    public function getIdVrac()
    {
    	if ($values = $this->getValues()) {
    		$prefixe = null;
    		$annee = ($values['annee'])? $values['annee'] : date('Y');
    		if (preg_match('/^([0-9]{4})([a-zA-Z0-9]{1})$/', $annee, $m)) {
    			$annee = $m[1];
    			$prefixe = $m[2];
    		}
    		$bordereau = ($values['bordereau'])? $values['bordereau'] : null;
    		return VracClient::getInstance()->buildNumeroContrat($annee, 0, $bordereau, $prefixe);
    	}
    	return null;
    }

}
