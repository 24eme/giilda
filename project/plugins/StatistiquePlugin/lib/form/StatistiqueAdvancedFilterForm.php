<?php
class StatistiqueAdvancedFilterForm extends BaseForm
{
	protected $filters;

	public function __construct($filters = null, $defaults = array(), $options = array(), $CSRFSecret = null)
	{
		$this->filters = $filters;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	
	public function configure() 
	{
		$this->setWidgets(array(
				'fk' => new sfWidgetFormChoice(array('choices' => array_merge(array('' => ''), $this->filters))),
				'fv' => new sfWidgetFormInputText(),
		));
		
		$this->widgetSchema->setLabels(array(
				'fk' => 'Filtre',
				'fv' => 'Valeur',
		));
		
		$this->setValidators(array(
				'fk' => new sfValidatorChoice(array('choices' => array_keys(array_merge(array('' => ''), $this->filters)), 'required' => false)),
				'fv' => new sfValidatorString(array('required' => false))
		));
	}
}