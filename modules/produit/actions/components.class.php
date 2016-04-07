<?php

class produitComponents extends sfComponents {

    const INTERPRO = "INTERPRO-declaration";

    public function executeItem() {
        try {
            $this->cvo = $this->produit->getCepage()->getDroitByType($this->date, ConfigurationDroits::DROIT_CVO, self::INTERPRO);
        } catch (Exception $e) {
            $this->cvo = null;
        }

        try {
            $this->douane = $this->produit->getCepage()->getDroitByType($this->date, ConfigurationDroits::DROIT_DOUANE, self::INTERPRO);
        } catch (Exception $e) {
            $this->douane = null;
        }
    }
}
