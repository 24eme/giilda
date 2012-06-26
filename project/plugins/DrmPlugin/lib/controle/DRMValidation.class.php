<?php
class DRMValidation
{
	private $drm;
	private $engagements;
	private $warnings;
	private $errors;
	const VINSSANSIG_KEY = 'VINSSANSIG';
	const AOP_KEY = 'AOP';
	const IGP_KEY = 'IGP';
	
	public function __construct($drm, $options = null)
	{
		$this->drm = $drm;
		$this->options = $options;
		$this->engagements = array();
		$this->warnings = array();
		$this->errors = array();
		$this->controleDrm();
	}
	
	public function getEngagements()
	{
		return $this->engagements;
	}
	
	public function getWarnings()
	{
		return $this->warnings;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	private function controleDrm()
	{
		$totalEntreeDeclassement = 0;
		$totalSortiDeclassement = 0;
		$certificationVinssansig = null;
		foreach ($this->drm->declaration->certifications as $certification) {
			if ($certification->getKey() == self::VINSSANSIG_KEY) {
				$certificationVinssansig = $certification;
			}
			$totalEntreeRepli = 0;
			$totalSortiRepli = 0;
			$details = $certification->getProduits();
			foreach ($details as $detail) {
				$this->controleEngagements($detail);
				$this->controleErrors($detail);
				$this->controleWarnings($detail);
				$totalEntreeRepli += $detail->entrees->repli;
				$totalSortiRepli += $detail->sorties->repli;
				$totalSortiDeclassement += $detail->sorties->declassement;
				if ($certification->getKey() == self::AOP_KEY && $detail->sorties->repli) {
					$this->engagements['odg'] = new DRMControleEngagement('odg');
				}
				if ($certification->getKey() == self::IGP_KEY && $detail->entrees->declassement) {
					$this->engagements['odg'] = new DRMControleEngagement('odg');
				}
				if ($certification->getKey() == self::VINSSANSIG_KEY) {
					$totalEntreeDeclassement += $detail->entrees->declassement;
				}
			}
			if ($totalEntreeRepli != $totalSortiRepli) {
				$this->errors['repli_'.$certification->getKey()] = new DRMControleError('repli', $this->generateUrl('drm_recap', $certification));
			}
		}
		if ($totalEntreeDeclassement > $totalSortiDeclassement) {
			$this->warnings['declassement_'.$certificationVinssansig->getKey()] = new DRMControleWarning('declassement', $this->generateUrl('drm_recap', $certificationVinssansig));
		}
		if ($drmSuivante = $this->drm->getSuivante()) {
			if ($this->drm->declaration->total != $drmSuivante->declaration->total_debut_mois) {
				$this->errors['stock'] = new DRMControleError('stock', '#');
			}
		}
	}
	
	public function isValide() {
	  return !($this->hasErrors());
	}
	
	private function controleEngagements($detail)
	{
		if ($detail->sorties->export > 0) {
			$this->engagements['export'] = new DRMControleEngagement('export');
		}
		if ($detail->sorties->declassement > 0) {
			$this->engagements['declassement'] = new DRMControleEngagement('declassement');
		}
		if ($detail->sorties->repli > 0) {
			$this->engagements['repli'] = new DRMControleEngagement('repli');
		}
		if ($detail->sorties->pertes > 0) {
			$this->engagements['pertes'] = new DRMControleEngagement('pertes');
		}
	}
	
	private function controleErrors($detail)
	{
		$totalVolume = 0;
		if (!isset($this->options['no_vrac']) || ! $this->options['no_vrac']) {
		  foreach ($detail->vrac as $contrat) {
		    $totalVolume += $contrat->volume;
		  }
		  if ($totalVolume < $detail->sorties->vrac) {
		    $this->errors['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleError('vrac', $this->generateUrl('drm_recap_detail', $detail).'#sorties');
		  } elseif ($totalVolume > $detail->sorties->vrac) {
		  	$this->errors['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleError('vrac', $this->generateUrl('drm_vrac', $this->drm).'#sorties');
		  }
		}
		if ($detail->total < 0) {
			$this->errors['total_negatif_'.$detail->getIdentifiantHTML()] = new DRMControleError('total_negatif', $this->generateUrl('drm_recap_detail', $detail));
		}
		if ($detail->total < ($detail->stocks_fin->bloque + $detail->stocks_fin->instance)) {
			$this->errors['total_stocks_'.$detail->getIdentifiantHTML()] = new DRMControleError('total_stocks', $this->generateUrl('drm_recap_detail', $detail));
		}
	}
	
	private function controleWarnings($detail)
	{
		if ($detail->sorties->mouvement > 0) {
			$this->warnings['mouvement_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mouvement', $this->generateUrl('drm_recap_detail', $detail).'#sorties');
		}
	}
	
	public function hasEngagements()
	{
		return (count($this->engagements) > 0)? true : false;
	}
	
	public function hasErrors()
	{
		return (count($this->errors) > 0)? true : false;
	}
	
	public function hasWarnings()
	{
		return (count($this->warnings) > 0)? true : false;
	}

	public function find($type, $identifiant)
	{
		if ($type == 'error' && array_key_exists($identifiant, $this->errors)) {

			return $this->errors[$identifiant];
		} elseif($type == 'warning' && array_key_exists($identifiant, $this->warnings)) {

			return $this->warnings[$identifiant];
		} elseif($type == 'engagement' && array_key_exists($identifiant, $this->engagements)) {
			
			return $this->engagements[$identifiant];
		}

		return null;
	}
	
	protected function generateUrl($route, $params = array(), $absolute = false)
	{
	  try {
	    return sfContext::getInstance()->getRouting()->generate($route, $params, $absolute);
	  }catch(Exception $e) {
	    return;
	  }
	}
	
}