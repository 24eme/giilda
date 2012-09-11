<?php

class Mouvement
{
    public function getMD5Key() {
        $key = $this->produit_hash . $this->type_hash . $this->detail_identifiant;
        if ($this->detail_identifiant)
            $key.= uniqid();
        return md5($key);
    }
}