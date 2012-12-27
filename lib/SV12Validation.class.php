<?php 

class SV12Validation extends DocumentValidation {

    public function configure()
    {
      $this->addControle('vigilance', 'contrats_nonsoldes', 'Il reste des contrats à saisir');
    }

    public function controle() {
      $nbnonsoldes = count($this->document->getContratsNonSaisis());
      if ($nbnonsoldes) {
	$this->addPoint('vigilance', 'contrats_nonsoldes', $nbnonsoldes.' contrat(s) soldé(s) trouvé(s)');
      }
    }
}