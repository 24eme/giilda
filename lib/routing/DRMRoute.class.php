<?php

class DRMRoute extends sfObjectRoute {

    protected $drm = null;
    
    protected function getObjectForParameters($parameters) {

        $this->drm = DRMClient::getInstance()->find('DRM-'.$parameters['identifiant'].'-'.$parameters['periode_version']);

        if (!$this->drm) {
            throw new sfError404Exception(sprintf('No DRM found for this periode-version "%s".',  $parameters['periode_version']));
        }
		if (isset($this->options['must_be_valid']) && $this->options['must_be_valid'] === true && !$this->drm->isValidee()) {
			throw new sfError404Exception('DRM must be validated');
		}
		if (isset($this->options['must_be_not_valid']) && $this->options['must_be_not_valid'] === true && $this->drm->isValidee()) {
			throw new sfError404Exception('DRM must not be validated');
		}
        return $this->drm;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("identifiant" => $object->getIdentifiant(), "periode_version" => $object->getPeriodeAndVersion());
        return $parameters;
    }
    
    public function getDRMConfiguration() {
        return ConfigurationClient::getCurrent();
    }

    public function getDRM() {
        if (!$this->drm) {
            $this->getObject();
        }

        return $this->drm;
    }

}