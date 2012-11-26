<?php
/**
 * Model for DSProduit
 *
 */

class DSProduit extends BaseDSProduit {

    function updateProduit($produit)
    {
        $this->produit_hash = $produit->getHash();
        $this->code_douane = $this->getConfig()->getCodeDouane();
        $this->produit_libelle = $produit->getLibelle("%g% %a% %m% %l% %co% %ce% %la%");
        $this->stock_initial = $produit->total;
        $this->stock_revendique = 0;
    }

    public function getConfig() {
        $hash = substr($this->produit_hash,1,  strlen($this->produit_hash) - 1);
        if($ret = strstr($hash,'/details', true)) $hash=$ret;
        
     return ConfigurationClient::getCurrent()->get($hash);   
    }
}