<?php
class StatistiqueAdvancedFiltersForm extends BaseForm
{
	protected $filters;

	public function __construct($filters = null, $defaults = array(), $options = array(), $CSRFSecret = null)
	{
		$this->filters = $filters;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	
	public function configure() 
	{
		$this->embedForm(uniqid(), new StatistiqueAdvancedFilterForm($this->filters));
	}
	
	public function bind(array $taintedValues = null, array $taintedFiles = null)
	{
		foreach ($this->embeddedForms as $key => $form) {
			if(!array_key_exists($key, $taintedValues)) {
				$this->unEmbedForm($key);
			}
		}
		foreach($taintedValues as $key => $values) {
			if(!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
				continue;
			}
			$this->embedForm($key, new StatistiqueAdvancedFilterForm($this->filters));
        }
	}

	public function unEmbedForm($key)
	{
		unset($this->widgetSchema[$key]);
		unset($this->validatorSchema[$key]);
		unset($this->embeddedForms[$key]);
	}
}