<?php

class DRMValidationObservationsCollectionForm extends BaseForm
{
        protected $_drm;

    	public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        	$this->_drm = $drm;
        	parent::__construct(array(), $options, $CSRFSecret);
    	}

        public function configure()
        {
			foreach ($this->_drm->getDetails() as $hash => $detail) {
                $this->embedForm ($hash, new DRMObservationForm($detail));
			}
    	}
}
