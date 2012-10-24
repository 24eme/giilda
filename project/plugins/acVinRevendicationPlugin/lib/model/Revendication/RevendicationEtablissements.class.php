<?php

/**
 * Model for RevendicationEtablissements
 *
 */
class RevendicationEtablissements extends BaseRevendicationEtablissements {

    public function storeProduits($num_ligne, $row,$hashLibelle) {
            $hash = $hashLibelle[0];
            $produit_hash = str_replace('/', '-', $hash);
            $produit_to_store = $this->produits->add($produit_hash);
            $produit_to_store->storeProduit($num_ligne,$row,$hashLibelle);
    }
    
    public function updateProduits($old_hash ,$new_hash, $new_libelle) {
        $old_hash_key = str_replace('/', '-', $old_hash);
        $new_hash_key = str_replace('/', '-', $new_hash);
        $old_produit = $this->get($old_hash_key);
        if(!$this->exist($new_hash_key))
        {
        $old_produit->updateProduit($new_hash, $new_libelle);
        $this->add($new_hash_key,$old_produit);
        }
        $this->remove($old_hash_key);
        
    }
    
    public function storeDeclarant($etb) {
        $this->declarant_identifiant = $etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_ID];
        $this->declarant_nom = $etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_NOM];
    }
    
}