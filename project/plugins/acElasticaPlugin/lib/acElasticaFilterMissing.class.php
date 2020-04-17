<?php

class acElasticaFilterMissing extends Elastica_Filter_Missing {
	
	const FILTER_TYPE = 'missing';
    
	protected function _getBaseName()
    {
        return self::FILTER_TYPE;
    }
}