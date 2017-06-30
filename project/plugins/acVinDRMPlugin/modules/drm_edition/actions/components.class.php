<?php

class drm_editionComponents extends sfComponents {

    public function executeItemForm() {
        if (is_null($this->form)) {
            $this->form = new DRMDetailForm($this->detail);
        }
    }

    public function executeProduitForm() {
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
    	$this->form = new DRMProduitForm($this->drm, $this->config, $this->detailsKey, $this->isTeledeclarationMode);
    }

    private function isTeledeclarationDrm() {
        return $this->getUser()->hasTeledeclarationDrm();
    }
}
