<?php 

class VracValidation {

    protected $vrac = null;
    protected $errors = array();
    protected $warnings = array();

    public function __construct($vrac, $options = null)
    {
        $this->vrac = $vrac;
        $this->check();
    }

    public function check() {
        if(is_null($this->vrac->volume_propose)) {
            $this->errors['volume_exist'] = "Le volume du contrat est manquant";
        }

        if(is_null($this->vrac->prix_unitaire)) {
            $this->errors['volume_exist'] = "Le prix du contrat est manquant";
        }

        if ($this->vrac->isRaisinMoutNegoHorsIL()) {
            $this->errors['hors_interloire_raisins_mouts'] = "Le négociant ne fait pas parti d'Interloire et le contrat est un contrat de raisins/mouts";
        }

        if ($this->vrac->isVin() && $this->vrac->volume_propose > $this->vrac->getStockCommercialisable()) {
            $this->warnings['stock_commercialisable_negatif'] = "Le stock commercialisable est inférieur au stock proposé";
        }

        return $this->isValid();
    }

    public function getErrors() {

        return $this->errors; 
    }

    public function getWarnings() {

        return $this->warnings; 
    }

    public function isValid() {

        return count($this->errors) == 0;
    }
}