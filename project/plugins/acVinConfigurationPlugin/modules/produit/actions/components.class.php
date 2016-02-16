<?php

class produitComponents extends sfComponents {

    public function executeItem() {
        try {
            $this->cvo = $this->produit->getCepage()->getDroitByType($this->date, 'INTERPRO-inter-loire', ConfigurationDroits::DROIT_CVO);
            $this->douane = $this->produit->getCepage()->getDroitByType($this->date, 'INTERPRO-inter-loire', ConfigurationDroits::DROIT_DOUANE);
        } catch (Exception $e) {
            $this->cvo = null;
            $this->douane = null;
        }
    }

    public function executeIndex() {
        $configuration = ConfigurationClient::getConfiguration($this->date);

        
        $this->produits = $configuration->declaration->getProduits($this->date);
    }

}