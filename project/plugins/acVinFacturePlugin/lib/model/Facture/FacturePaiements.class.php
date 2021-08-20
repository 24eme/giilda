<?php
/**
 * Model for FacturePaiements
 *
 */

class FacturePaiements extends BaseFacturePaiements {


  public function getPaiementsTotal(){
    $total_paiement = 0.0;
    foreach($this as $paiement) {
        $total_paiement+= $paiement->montant;
    }
    return $total_paiement;
  }


}
