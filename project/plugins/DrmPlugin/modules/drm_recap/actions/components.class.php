<?php

class drm_recapComponents extends sfComponents {

    public function executeList() {
        $this->produits = $this->drm_lieu->getProduits();
    }
    
    public function executeItemForm() {
        if (is_null($this->form)) {
            $this->form = new DRMDetailForm($this->detail);
        }
    }
    
    public function executeOnglets() {
        $this->items = $this->drm_lieu->getCertification()->getLieuxArray();
    }

}
