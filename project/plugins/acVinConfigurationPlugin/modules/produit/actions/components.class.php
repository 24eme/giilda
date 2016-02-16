<?php

class produitComponents extends sfComponents {

    const INTERPRO = "INTERPRO-declaration";

    public function executeItem() {
        try {
            $this->cvo = $this->produit->getCepage()->getDroitByType($this->date, self::INTERPRO, ConfigurationDroits::DROIT_CVO);
            $this->taux_str = (!is_null($this->cvo)) ? $this->cvo->getStringTaux() : null;
            $this->taux_calc = "";
            if (is_array($this->cvo->taux)) {
                $this->taux_calc = $this->produit->getCepage()->getDroits(self::INTERPRO)->get(ConfigurationDroits::DROIT_CVO)->getCurrentDroit(date("Y-m-d"), false)->taux;
                $this->taux_calc = $this->produit->getCepage()->getDroits(self::INTERPRO)->get(ConfigurationDroits::DROIT_CVO)->getCurrentDroit(date("Y-m-d"))->taux;
                $this->taux_calc = ($this->cvo->taux[0] == "+") ? $this->taux_calc - floatval($this->cvo->taux[1]) : $this->taux_calc;
                $this->taux_calc = ($this->cvo->taux[0] == "-") ? $this->taux_calc + floatval($this->cvo->taux[1]) : $this->taux_calc;
            }
            $this->douane = $this->produit->getCepage()->getDroits(self::INTERPRO)->get(ConfigurationDroits::DROIT_DOUANE)->getCurrentDroit(date("Y-m-d"), false);
        } catch (Exception $e) {
            $this->cvo = null;
            $this->taux_str = null;
            $this->taux_calc = null;
            $this->douane = null;
        }
    }

    public function executeIndex() {
        $configuration = ConfigurationClient::getConfiguration($this->date);
        $this->produits = $configuration->declaration->getProduits($this->date);
    }

}
