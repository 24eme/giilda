<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteChoiceForlm
 * @author mathurin
 */
class SocieteChoiceFormX extends baseForm {
	
	protected $interpro_id;
	
  	public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro_id = $interpro_id;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}

    public function configure()
    {
        $this->setWidget('identifiant', new WidgetSociete(array('interpro_id' => $this->interpro_id), array('class' => 'autocomplete permissif')));
        $this->setWidget('societeType', new sfWidgetFormChoice(array('choices' => $this->getSocieteTypes(),'expanded' => false)));
        

        $this->widgetSchema->setLabel('identifiant', 'Nom de la société :');
        $this->widgetSchema->setLabel('societeType', 'Type de société : ');        
        
        $this->setValidator('identifiant', new ValidatorSociete(array('required' => true)));
        $this->setValidator('societeType', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getSocieteTypes()))));
        
        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'une societe est obligatoire');        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        
        $this->widgetSchema->setNameFormat('societe[%s]');
    }

    public function getSocieteTypes() {
        return SocieteClient::getSocieteTypes();
    }

    public function getSociete() {

        return $this->getValidator('identifiant')->getDocument();
    }
    
}
