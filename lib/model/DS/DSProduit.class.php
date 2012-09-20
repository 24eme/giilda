<?php
/**
 * Model for DSProduit
 *
 */

class DSProduit extends BaseDSProduit {

    function updateProduit($produit)
    {
        $this->produit_hash = $produit->getHash();
        $this->produit_libelle = $produit->getLibelle();
        $this->produit_type = 'Vin';
        $this->stock_consome = 0;
        $this->stock_initial = $produit->total;
        $this->stock_revendique = 0;
        $this->stock_theorique = 0;   
        $this->vente_vin = 0;
    }
}