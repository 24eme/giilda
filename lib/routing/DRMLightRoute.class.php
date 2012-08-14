<?php

class DRMLightRoute extends sfRequestRoute {

	protected $drm = null;


	protected function getDRMForParameters($parameters) {
        $id = 'DRM-'.$parameters['identifiant'].'-'.$parameters['periode_version'];

        $drm = DRMClient::getInstance()->find($id);

        if (!$drm) {
            throw new sfError404Exception(sprintf("The document '%s' not found", $id));
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