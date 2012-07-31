<?php

class DRMLightRoute extends sfRequestRoute {

	protected $drm = null;


	protected function getDRMForParameters($parameters) {

        if (preg_match('/^[0-9]{4}-[0-9]{2}$/', $parameters['campagne_rectificative'])) {
            $campagne = $parameters['campagne_rectificative'];
            $rectificative = null;
        } elseif(preg_match('/^([0-9]{4}-[0-9]{2})-R([0-9]{2})$/', $parameters['campagne_rectificative'], $matches)) {
            $campagne = $matches[1];
            $rectificative = $matches[2];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'campagne_rectificative', $parameters['campagne_rectificative']));
        }

        $drm = DRMClient::getInstance()->findByIdentifiantCampagneAndRectificative($parameters['identifiant'], 
                                                                                         $campagne, 
                                                                                         $rectificative);

        if (!$drm) {
            throw new sfError404Exception(sprintf('No DRM found for this campagne-rectificative "%s".',  $parameters['campagne_rectificative']));
        }
	
		if (isset($this->options['must_be_valid']) && $this->options['must_be_valid'] === true && !$drm->isValidee()) {
			throw new sfError404Exception('DRM must be validated');
		}
		if (isset($this->options['must_be_not_valid']) && $this->options['must_be_not_valid'] === true && $drm->isValidee()) {
			throw new sfError404Exception('DRM must not be validated');
		}
        return $drm;
    }


    public function getDRM() {
        if (is_null($this->drm)) {
            $this->drm = $this->getDRMForParameters($this->parameters);
        }

        return $this->drm;
    }

}