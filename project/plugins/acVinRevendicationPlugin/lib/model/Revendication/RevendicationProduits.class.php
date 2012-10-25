<?php
/**
 * Model for RevendicationProduits
 *
 */

class RevendicationProduits extends BaseRevendicationProduits {

    const STATUT_SUPPRIME = 'supprime';
    const STATUT_MODIFIE = 'modifie';
    const STATUT_IMPORTE = 'importe';
    
    public function storeProduit($num_ligne,$row,$hashLibelle) {
        $this->libelle_produit_csv = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        $this->produit_hash = $hashLibelle[0];
        $this->produit_libelle = $hashLibelle[1];
        $this->date_certification = $row[RevendicationCsvFile::CSV_COL_DATE];
        $this->statut = self::STATUT_IMPORTE;
        if(!array_key_exists($row[RevendicationCsvFile::CSV_COL_UNKOWN_ID2],$this->volumes)){
           $volumes = $this->volumes->add($row[RevendicationCsvFile::CSV_COL_UNKOWN_ID2]);
           
           $volumes->volume = floatval($row[RevendicationCsvFile::CSV_COL_UNKNOWN_ID1]);
           $volumes->num_ligne = $num_ligne;
          }
        
    }
    
    public function updateProduit($hash,$libelle) {
        $this->produit_hash = $hash;
        $this->produit_libelle = $libelle;
        $this->statut = self::STATUT_MODIFIE;
    }
    
    public function supprProduit() {
        $this->statut = self::STATUT_SUPPRIME;
    }
}