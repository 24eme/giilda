<?php

class produitComponents extends sfComponents {

    public function executeItem() {
        $droit_cvo = $this->produit->getCepage()->getDroits('INTERPRO-inter-loire')->get(ConfigurationDroits::DROIT_CVO);
        $droit_douane = $this->produit->getCepage()->getDroits('INTERPRO-inter-loire')->get(ConfigurationDroits::DROIT_DOUANE);
        
        try {
            $this->cvo = $this->produit->getCepage()->getDroits('INTERPRO-inter-loire')->get(ConfigurationDroits::DROIT_CVO)->getCurrentDroit(date("Y-m-d"));
            $this->douane = $this->produit->getCepage()->getDroits('INTERPRO-inter-loire')->get(ConfigurationDroits::DROIT_DOUANE)->getCurrentDroit(date("Y-m-d"));
        } catch (Exception $e) {
            $this->cvo = null;
            $this->douane = null;
        }
    }

    public function executeIndex() {
        $this->produits = ConfigurationClient::getCurrent()->declaration->getProduitsWithCVONeg();
    }

}