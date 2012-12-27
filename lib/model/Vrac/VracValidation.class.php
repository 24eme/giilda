<?php 

class VracValidation extends DocumentValidation{

    public function configure()
    {
      $this->addControle('erreur', 'volume_expected', 'Le volume du contrat est manquant');
      $this->addControle('erreur', 'prix_initial_expected', 'Le prix du contrat est manquant');
      $this->addControle('erreur', 'hors_interloire_raisins_mouts', "Le négociant ne fait pas parti d'Interloire et le contrat est un contrat de raisins/moûts");
      $this->addControle('vigilance', 'stock_commercialisable_negatif', 'Le stock commercialisable est inférieur au stock proposé');
      $this->addControle('vigilance', 'contrats_similaires', 'Risque de doublons');
      $this->addControle('vigilance', 'contrats_similaires', 'Risque de doublons');
      $this->addControle('vigilance', 'prix_definitif_expected', "Le prix définitif de contrat n'a pas été saisi");
    }

    public function controle() {
        if(!$this->document->volume_propose) {
	  $this->addPoint('erreur', 'volume_expected', 'saisir un volume', $this->generateUrl('vrac_marche', $this->document));
        }

        if(is_null($this->document->prix_initial_unitaire)) {
	  $this->addPoint('erreur', 'prix_initial_expected', 'saisir un prix', $this->generateUrl('vrac_marche', $this->document));
        }

        if($this->document->hasPrixVariable() && !$this->document->hasPrixDefinitif()) {
    $this->addPoint('vigilance', 'prix_definitif_expected', 'saisir le prix définitif', $this->generateUrl('vrac_marche', $this->document));
        }

        if ($this->document->isRaisinMoutNegoHorsIL()) {
	  $this->addPoint('erreur', 'hors_interloire_raisins_mouts', 'changer' , $this->generateUrl('vrac_soussigne', $this->document));
        }

        if ($this->document->isVin() && $this->document->volume_propose > $this->document->getStockCommercialisable()) {
	  $this->addPoint('vigilance', 'stock_commercialisable_negatif', 'modifier le volume' , $this->generateUrl('vrac_marche', $this->document));
        }

	$nbsimilaires = count(array_keys(VracClient::getInstance()->retrieveSimilaryContracts($this->document)));
	if ($nbsimilaires) {
	  $this->addPoint('vigilance', 'contrats_similaires', 'Il y a '.$nbsimilaires.' contrat(s) similaire(s)');
	}
    }
}