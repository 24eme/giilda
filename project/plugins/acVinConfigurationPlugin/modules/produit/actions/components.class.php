<?php

class produitComponents extends sfComponents {

    public function executeItem() {
        try {
            $this->cvo = $this->produit->getCepage()->getDroitByType($this->date, 'INTERPRO-inter-loire', ConfigurationDroits::DROIT_CVO);
        } catch (Exception $e) {
            $this->cvo = null;
        }

        try {
            $this->douane = $this->produit->getCepage()->getDroitByType($this->date, 'INTERPRO-inter-loire', ConfigurationDroits::DROIT_DOUANE);
        } catch (Exception $e) {
            $this->douane = null;
        }
    }

}