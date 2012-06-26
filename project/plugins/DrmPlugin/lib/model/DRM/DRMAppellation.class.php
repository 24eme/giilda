<?php
/**
 * Model for DRMCertification
 */

class DRMAppellation extends BaseDRMAppellation {
    
    /**
     *
     * @return DRMCertification
     */
    public function getGenre() {

        return $this->getParentNode();
    }

    public function getChildrenNode() {

        return $this->mentions;
    }

     /**
     *
     * @return DRMGenre
     */
    public function getCertification() {
        return $this->getGenre()->getParent()->getParent();
    }
    
    public function updateDroits($droits) {
    	$merge = array();
    	if ($this->getDocument()->getInterpro()->identifiant == Interpro::INTER_RHONE_ID) {
    		$merge = DRMDroits::getDroitSortiesInterRhone();
    	}
    	foreach ($this->getDroits() as $typedroits => $droit) {
    		$droits->add($typedroits)->add($droit->code)->integreVolume($this->sommeLignes(DRMDroits::getDroitSorties($merge)), $this->sommeLignes(DRMDroits::getDroitEntrees()), $droit->taux, $this->getReportByDroit($droit), $droit->libelle);
    	}
    }


    public function getReportByDroit($droit) {
    	$drmPrecedente = $this->getDocument()->getPrecedente();
    	if ($drmPrecedente->isNew()) {
    		return 0;
    	} else {
    		if ($drmPrecedente->get('droits')->get('douane')->exist($droit->code)) {
    			return $drmPrecedente->get('droits')->get('douane')->get($droit->code)->cumul;
    		} else {
    			return 0;
    		}
    	}
    }

    public function sommeLignes($lines) {
      $sum = 0;
      foreach($this->mentions as $mention) {
	$sum += $mention->sommeLignes($lines);
      }
      return $sum;
    }
    
    public function getDroit($type) {
      return $this->getConfig()->getDroits($this->getInterproKey())->get($type)->getCurrentDroit($this->getCampagne());
    }

    public function getDroits() {
      $conf = $this->getConfig();
      $droits = array();
      foreach ($conf->getDroits($this->getInterproKey()) as $key => $droit) {
	$droits[$key] = $droit->getCurrentDroit($this->getCampagne());
      }
      return $droits;
    }
    public function getInterproKey() {
      return $this->getDocument()->getInterpro()->get('_id');
    }
    public function getCampagne() {
      return $this->getDocument()->getCampagne();
    }
}