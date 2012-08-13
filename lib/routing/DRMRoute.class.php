<?php

class DRMRoute extends sfObjectRoute {

    protected $drm = null;
    
    protected function getObjectForParameters($parameters) {

        if (preg_match('/^[0-9]{4}-[0-9]{2}$/', $parameters['periode_version'])) {
            $periode = $parameters['periode_version'];
            $rectificative = null;
        } elseif(preg_match('/^([0-9]{4}-[0-9]{2})-R([0-9]{2})$/', $parameters['periode_version'], $matches)) {
            $periode = $matches[1];
            $rectificative = $matches[2];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'periode_version', $parameters['periode_version']));
        }

        $this->drm = DRMClient::getInstance()->findByIdentifiantPeriodeAndVersion($parameters['identifiant'], 
                                                                                         $periode, 
                                                                                         $rectificative);

        if (!$this->drm) {
            throw new sfError404Exception(sprintf('No DRM found for this periode-rectificative "%s".',  $parameters['periode_version']));
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