<?php
/**
 * Model for DRMESDetailVrac
 *
 */

class DRMESDetailVrac extends BaseDRMESDetailVrac {

    const CONTRAT_VRAC_SANS_NUMERO = "VRAC-SANSNUMERO";
    const CONTRAT_BOUTEILLE_SANS_NUMERO = "BOUTEILLE-SANSNUMERO";

    protected $vrac = null;

    public function getProduitDetail() {

        return $this->getParent()->getProduitDetail();
    }

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->vrac = VracClient::getInstance()->find($this->identifiant);
        }

        return $this->vrac;
    }

    public function isSansContrat() {

        return in_array($this->identifiant, array(self::CONTRAT_VRAC_SANS_NUMERO, self::CONTRAT_BOUTEILLE_SANS_NUMERO));
    }

    public function getIdentifiantLibelle() {
        if($this->isSansContrat() && $this->identifiant == self::CONTRAT_BOUTEILLE_SANS_NUMERO) {

            return "Bouteille";
        }

        if($this->isSansContrat() && $this->identifiant == self::CONTRAT_VRAC_SANS_NUMERO) {

            return "Vrac";
        }

        return $this->getVrac()->getNumeroArchive();
    }
}
