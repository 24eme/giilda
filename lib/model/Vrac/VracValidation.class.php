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
        if(!$this->vrac->volume_propose) {
            $this->errors['volume_exist'] = "Le volume du contrat est manquant";
        }

        if(is_null($this->vrac->prix_unitaire)) {
            $this->errors['volume_exist'] = "Le prix du contrat est manquant";
        }

        if ($this->vrac->isRaisinMoutNegoHorsIL()) {
            $this->errors['hors_interloire_raisins_mouts'] = "Le négociant ne fait pas parti d'Interloire et le contrat est un contrat de raisins/moûts";
        }

        if ($this->vrac->isVin() && $this->vrac->volume_propose > $this->vrac->getStockCommercialisable()) {
            $this->warnings['stock_commercialisable_negatif'] = "Le stock commercialisable est inférieur au stock proposé";
        }

	$nbsimilaires = count(VracClient::getInstance()->retrieveSimilaryContracts($this->vrac));
	if ($nbsimilaires) {
	  $this->warnings['contrat_similaires'] = 'Il y a '.$nbsimilaires.' contrat(s) similaire(s)';
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