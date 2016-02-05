<?php
class StatistiqueFilterForm extends BaseForm
{
  	
	public function configure() 
	{
		$this->setWidgets(array(
				'q' => new sfWidgetFormInputText()
		));
		
		$this->widgetSchema->setLabels(array(
				'q' => 'Rechercher'
		));
		
		$this->setValidators(array(
				'q' => new sfValidatorString(array('required' => false))
		));
        $this->widgetSchema->setNameFormat('statistique_filter[%s]');
    }
    
    public function getDefaultQuery()
    {
    	$query_string = new acElasticaQueryQueryString('*');
    	return $query_string;
    }
    
    public function getQuery()
    {
    	$q = ($this->getValue('q'))? $this->getValue('q') : '*';
    	$query_string = new acElasticaQueryQueryString($q);
    	return $query_string;
    }
}