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
        $this->stock_declare = null;
    }

    function updateProduitFromConfig($produit)
    {
        $this->produit_hash = $produit->getHash();
        $this->code_douane = $produit->getCodeDouane();
        $this->produit_libelle = $produit->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
        $this->stock_initial = 0;
        $this->stock_declare = null;
    }

    public function getConfig() {
        $hash = substr($this->produit_hash,1,  strlen($this->produit_hash) - 1);
        if($ret = strstr($hash,'/details', true)) $hash=$ret;
        
        return ConfigurationClient::getCurrent()->get($hash);   
    }

    public function isActif() {

        return (!is_null($this->stock_declare));
    }
    
    public function hasElaboration(){
        return strstr($this->produit_hash, 'EFF')!==false;
    }
}
