<?php

class DRMObservationsCollectionForm extends BaseForm
{
        protected $drm;

    	public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        	$this->drm = $drm;
        	parent::__construct(array(), $options, $CSRFSecret);
    	}

        public function configure()
        {
			foreach ($this->drm->getProduitsDetails() as $hash => $detail) {
                if($detail->exist('observations')){
                  $this->embedForm($hash, new DRMObservationForm($detail));
                  }
        	}

            foreach ($this->drm->crds as $regime => $crdsRegime) {
                foreach ($crdsRegime as $nodeName => $crd) {
                    if ($crd->exist('observations')) {
                        $this->embedForm($crd->getHash(), new DRMObservationForm($crd));
                    }
                }
            }
    }
}
