<?php

class acElasticaFilterExists extends Elastica_Filter_Exists {
	
	const FILTER_TYPE = 'exists';
    
	protected function _getBaseName()
    {
        return self::FILTER_TYPE;
    }
}