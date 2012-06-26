<?php

class DRMVracDetailRoute extends DrmDetailRoute {
    
    protected function getObjectForParameters($parameters) {
    	$config_detail = parent::getObjectForParameters($parameters);
    	return $this->getDrm()->get($config_detail->getHash())->vrac->get($parameters['contrat']);
    }

    protected function doConvertObjectToArray($object) {
        $parameters = parent::doConvertObjectToArray($object->getParent()->getParent());
        $parameters['contrat'] = $object->getKey();
        
        return $parameters;
    }

}