<?php

/**
 * Model for RevendicationEtablissements
 *
 */
class RevendicationEtablissements extends BaseRevendicationEtablissements {

    public function storeProduits($num_ligne, $row,$hashLibelle, $bailleur) {
            $produit_to_store = $this->produits->add($row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT]);
            $produit_to_store->storeProduit($num_ligne,$row,$hashLibelle, $bailleur);
    }
    
    public function updateProduits($old_hash ,$new_hash, $new_libelle) {
        $old_hash_key = str_replace('/', '-', $old_hash);
        $new_hash_key = str_replace('/', '-', $new_hash);
        $old_produit = $this->produits->get($old_hash_key);
        if(!$this->exist($new_hash_key))
        {
            $old_produit->updateProduit($new_hash, $new_libelle);
            $this->produits->add($new_hash_key,$old_produit);
        }
        $this->produits->get($old_hash_key)->supprProduit();
    }
    
    public function storeDeclarant($etb) {
        $this->declarant_cvi = $etb->key[EtablissementFindByCviView::KEY_ETABLISSEMENT_CVI];
        $this->declarant_nom = $etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_NOM];
        $this->commune = $etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_COMMUNE];
    }
    
}