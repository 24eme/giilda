<?php

/**
 * Model for DRMDroit
 *
 */
class DRMDroit extends BaseDRMDroit {

    private $virtual = 0;
    private $payable_total = 0;
    private $cumulable_total = 0;

    public function getVolume() {
        return $this->volume_taxe - $this->volume_reintegre;
    }

    public function updateTotal() {
        $this->total = ($this->volume_taxe - $this->volume_reintegre) * $this->taux;
        $this->cumul = $this->total;
        if ($this->getDocument()->isPaiementAnnualise()) {
            $this->cumul += floatval($this->report);
        }
    }

    public function clearDroitDouane() {
        $this->report = round($this->cumul);
        $this->total = null;
        $this->volume_taxe = null;
        $this->volume_reintegre = null;
        $this->code = null;
        $this->taux = null;
        $this->libelle = null;
        $mois = substr($this->getDocument()->periode, 4, 2);
        if (DRMPaiement::isDebutCampagne((int) $mois)) {
            $this->report = null;
        }
    }

}
