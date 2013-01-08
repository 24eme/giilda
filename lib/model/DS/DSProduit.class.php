<?php
/**
 * Model for DSProduit
 *
 */

class DSProduit extends BaseDSProduit {

    function updateProduit($produit)
    {
        $this->produit_hash = $produit->getHash();
        $this->produit_libelle = $produit->getLibelle("%g% %a% %m% %l% %co% %ce%");
        $this->stock_initial = $produit->total;
    }

    function updateProduitFromConfig($produit)
    {
        $this->produit_hash = $produit->getHash();
        $this->produit_libelle = $produit->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce%");
        $this->stock_initial = 0;
    }

    public function isActif() {

        return (!is_null($this->stock_declare));
    }
    
    public function hasElaboration(){
        return strstr($this->produit_hash, 'EFF')!==false;
    }
}
