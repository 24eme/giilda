<?php
class DRMValidation extends DocumentValidation
{
	public function configure() {
		$this->addControle('erreur', 'repli', "La somme des replis en entrée et en sortie n'est pas la même");
		$this->addControle('erreur', 'vrac_detail_nonsolde', "Le contrat est soldé (ou annulé)");
		$this->addControle('erreur', 'vrac_detail_exist', "Le contrat n'existe plus");
		
		$this->addControle('vigilance', 'total_negatif', "Le stock revendiqué théorique fin de mois est négatif");
		$this->addControle('vigilance', 'vrac_detail_negatif', "Le volume qui sera enlevé sur le contrat est supérieur au volume restant");
	}

	public function controle()
	{
		$total_entrees_replis = 0;
		$total_sorties_replis = 0;
	
		foreach ($this->document->getProduitsDetails() as $detail) {
			$total_entrees_replis += $detail->entrees->repli;
			$total_sorties_replis += $detail->sorties->repli;

			if($detail->total < 0) {
				$this->addPoint('vigilance', 'total_negatif', $detail->getLibelle(), $this->generateUrl('drm_edition_detail', $detail));
			}

			foreach($detail->sorties->vrac_details as $vrac_detail) {
				$vrac = $vrac_detail->getVrac();

				if(!$vrac) {
					$this->addPoint('erreur', 'vrac_detail_exist', sprintf("%s, Contrat %s", $detail->getLibelle(), $vrac_detail->identifiant), $this->generateUrl('drm_edition_detail', $detail));
					continue;
				}

				if ($vrac->valide->statut != VracClient::STATUS_CONTRAT_NONSOLDE) {
					$this->addPoint('erreur', 'vrac_detail_nonsolde', sprintf("%s, Contrat %s", $detail->getLibelle(), $vrac->__toString()), $this->generateUrl('vrac_visualisation', $vrac));
					continue;
				}

				$volume_restant = $vrac->volume_propose - ($vrac->volume_enleve + $vrac_detail->volume); 
				if($volume_restant < 0) {
					$this->addPoint('vigilance', 
									'vrac_detail_negatif', 
									sprintf("%s, Contrat %s (%01.02f hl enlevé / %01.02f hl proposé)", 
									     	$detail->getLibelle(), 
									     	$vrac->__toString(), 
									     	$vrac->volume_enleve + $vrac_detail->volume,
									     	$vrac->volume_propose), 
									$this->generateUrl('drm_edition_detail', $detail));
				}
			}
		}

		if ($total_entrees_replis != $total_sorties_replis) {
			$this->addPoint('erreur', 'repli', $detail->getLibelle(), $this->generateUrl('drm_edition', $this->document));
		}
	}
}