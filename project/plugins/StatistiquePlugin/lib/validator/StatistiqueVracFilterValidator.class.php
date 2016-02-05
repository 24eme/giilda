<?php
class StatistiqueVracFilterValidator extends sfValidatorBase
{
    
    protected function doClean($values) 
    {
    	if ($values['millesime']) {
    		$values['millesime'] = explode(';', $values['millesime']);
    	}
        return $values;
    }
	
}