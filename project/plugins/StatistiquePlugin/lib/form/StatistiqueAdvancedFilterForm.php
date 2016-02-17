<?php
class StatistiqueAdvancedFilterForm extends BaseForm
{
	protected $filters;
	protected $operators = array(
			'' => '=',
			'* -' => '!=',
			'>' => '>',
			'<' => '<',
			'>=' => '>=',
			'<=' => '<=',
	);

	public function __construct($filters = null, $defaults = array(), $options = array(), $CSRFSecret = null)
	{
		$this->filters = $filters;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	
	public function configure() 
	{
		$this->setWidgets(array(
				'fk' => new sfWidgetFormChoice(array('choices' => array_merge(array('' => ''), $this->filters))),
				'fo' => new sfWidgetFormChoice(array('choices' => $this->operators)),
				'fv' => new sfWidgetFormInputText(),
		));
		
		$this->widgetSchema->setLabels(array(
				'fk' => 'Filtre',
				'fo' => 'Opérateur',
				'fv' => 'Valeur',
		));
		
		$this->setValidators(array(
				'fk' => new sfValidatorChoice(array('choices' => array_keys(array_merge(array('' => ''), $this->filters)), 'required' => false)),
				'fo' => new sfValidatorChoice(array('choices' => array_keys($this->operators), 'required' => false)),
				'fv' => new sfValidatorString(array('required' => false))
		));
	}
}