<?php
/**
 * Model for SV12Mouvement
 *
 */

class SV12Mouvement extends BaseSV12Mouvement {
    
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
}