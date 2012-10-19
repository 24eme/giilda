<?php
/**
 * Model for RevendicationProduits
 *
 */

class RevendicationProduits extends BaseRevendicationProduits {

    public function storeProduit($num_ligne,$row,$hashLibelle) {
        $this->libelle_produit_csv = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        $this->produit_hash = $hashLibelle[0];
        $this->produit_libelle = $hashLibelle[1];
        if(!array_key_exists($row[RevendicationCsvFile::CSV_COL_UNKOWN_ID2],$this->volumes)){
           $volumes = $this->volumes->add($row[RevendicationCsvFile::CSV_COL_UNKOWN_ID2]);
           
           $volumes->volume = floatval($row[RevendicationCsvFile::CSV_COL_UNKNOWN_ID1]);
           $volumes->num_ligne = $num_ligne;
           }
        
    }
}