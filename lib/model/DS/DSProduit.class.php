<?php
/**
 * Model for DSProduit
 *
 */

class DSProduit extends BaseDSProduit {


    public function updateProduitFromDS($produit)
    {
        $this->updateProduitFromConfig($produit->getConfig());
    }

    public function updateProduitFromDRM($produit)
    {
        $this->updateProduitFromConfig($produit->getConfig());
        $this->stock_initial = $produit->total;
    }

    public function updateProduitFromConfig($produit_config)
    {
        $this->produit_hash = $produit_config->getHash();
        $this->produit_libelle = $produit_config->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce%");
        $this->code_douane = $produit_config->getCodeDouane();
    }

    public function isActif() {

        return (!is_null($this->stock_declare));
    }
    
    public function hasElaboration(){
        return strstr($this->produit_hash, 'EFF')!==false;
    }

    public function getConfig() {

        return ConfigurationClient::getCurrent()->get($this->produit_hash);
    }
}
