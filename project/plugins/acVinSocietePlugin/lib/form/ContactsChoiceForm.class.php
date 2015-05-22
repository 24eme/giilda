<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteChoiceForlm
 * @author mathurin
 */
class ContactsChoiceForm extends baseForm {
	
	protected $interpro_id;
	
  	public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
	  $this->interpro_id = $interpro_id;
	  parent::__construct($defaults, $options, $CSRFSecret);
  	}

    public function configure()
    {
        $this->setWidget('identifiant', new WidgetCompte(array('interpro_id' => $this->interpro_id), array('class' => 'autocomplete')));        
        $this->setValidator('identifiant', new ValidatorCompte(array('required' => true)));
        
        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'une societe ou d\'un etablissement ou d\'un interlocuteur est obligatoire');        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        
        $this->widgetSchema->setNameFormat('contacts[%s]');
    }

    public function getContact() {
      return $this->values['identifiant'];
    }

    
}
