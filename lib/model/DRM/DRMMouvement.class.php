<?php

/**
 * Model for DRMMouvement
 *
 */
class DRMMouvement extends BaseDRMMouvement {

    public function facturer() {
        $this->facture = 1;
    }

    public function defacturer() {
        $this->facture = 0;
    }

    public function getMD5Key() {
        $key = $this->getDocument()->identifiant . $this->produit_hash . $this->type_hash . $this->detail_identifiant;
        if ($this->detail_identifiant)
            $key.= uniqid();
        
        return md5($key);
    }

    public function isFacturable() {
        
        return $this->facturable;
    }

    public function isVrac() {

        return $this->vrac_numero;
    }

    public function getVrac() {

        if (!$this->isVrac()) {
            return null;
        }

        $vrac = VracClient::getInstance()->findByNumContrat($this->vrac_numero);

        if (!$vrac) {

            throw new sfException(sprintf("Le contrat '%s' n'a pas été trouvé", $this->vrac_numero));
        }

        return $vrac;
    }
}