<?php
/**
 * Model for DSProduit
 *
 */

class DSProduit extends BaseDSProduit {

    function updateProduit($produit)
    {
        $this->produit_hash = $produit->getHash();
        $this->produit_libelle = $produit->getLibelle("%g% %a% %m% %l% %co% %ce% %la%");
        $this->stock_initial = $produit->total;
        $this->stock_revendique = 0;
    }
}