<?php
/**
 * Model for DRMESDetailVrac
 *
 */

class DRMESDetailVrac extends BaseDRMESDetailVrac {

    protected $vrac = null;

    const CONTRAT_VRAC_SANS_NUMERO = "VRAC-SANSNUMERO";
    const CONTRAT_BOUTEILLE_SANS_NUMERO = "BOUTEILLE-SANSNUMERO";

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->vrac = VracClient::getInstance()->find($this->identifiant);
        }

        return $this->vrac;
    }

    public function getDateEnlevement(){
        if(!$this->_get('date_enlevement')){
            return $this->getDocument()->getDate();
        }

        return $this->_get('date_enlevement');
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

    public function setKey($k) {
      $this->key = $k;
    }

    public function getKey() {
        if (!isset($this->key) || !$this->key) {
            if (!($this->key = parent::getKey())) {
                $this->key = $this->identifiant.'-'.uniqid();
            }
        }
      return $this->key;
    }

}
