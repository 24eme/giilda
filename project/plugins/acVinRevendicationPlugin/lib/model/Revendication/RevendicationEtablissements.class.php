<?php

/**
 * Model for RevendicationEtablissements
 *
 */
class RevendicationEtablissements extends BaseRevendicationEtablissements {

    public function storeProduits($num_ligne, $row,$hashLibelle) {
            $hash = $hashLibelle[0];
            $produit_hash = str_replace('/', '-', $hash);
            $produit_to_store = $this->add($produit_hash);
            $produit_to_store->storeProduit($num_ligne,$row,$hashLibelle);
    }
}