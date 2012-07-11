<?php

class myUser extends sfBasicSecurityUser
{
	const CREDENTIAL_ADMIN = 'admin';

	protected $tiers = null;
	protected $drm = null;
	protected $historique = null;


	public function getTiers() {
		if (is_null($this->tiers)) {
			$this->tiers = EtablissementClient::getInstance()->findByIdentifiant('111849');
		}
		
		return $this->tiers;
	}

	public function getDrm() {
    	if (is_null($this->drm)) {
    		$lastDrm = $this->getDrmHistorique()->getLastDrm();

    		if ($lastDrm && $drm = DRMClient::getInstance()->find(key($lastDrm))) {
    			if (!$drm->isValidee()) {
    				$this->drm = $drm;
    			} else {
    				$this->drm = $drm->generateSuivante($this->getCampagneDrm());
    			}
    		} else {
    			$this->drm = new DRM();
    			$this->drm->identifiant = $this->getTiers()->identifiant;
    			$this->drm->campagne = $this->getCampagneDrm();
    		}
    	}
    	return $this->drm;
    }

    public function getDrmHistorique() {
    	if (is_null($this->historique)) {
        	$this->historique = new DRMHistorique($this->getTiers()->identifiant);
    	}

    	return $this->historique;
    }

    /**
     * @return string
     */
    public function getCampagneDrm() {
      
      return CurrentClient::getCurrent()->campagne;
    }

    public function createDRMByCampagne($campagne = null) {
    	if (!$campagne) {
    		$campagne = date('Y-m');
    	}
    	$campagneTab = explode('-', $campagne);
    	$date_campagne = new DateTime($campagneTab[0].'-'.$campagneTab[1].'-01');
       	$date_campagne->modify('-1 month');
       	$prev_campagne = DRMClient::getInstance()->getCampagne($date_campagne->format('Y'), $date_campagne->format('m'));
       	$prev_drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->getTiers()->identifiant, $prev_campagne);
       	if ($prev_drm) {
           $this->_drm = $prev_drm->generateSuivante($campagne);
       	} else {
       		$lastDRM = $this->getDRMHistorique()->getLastDRM();
    		if ($lastDRM && $drm = DRMClient::getInstance()->find(key($lastDRM))) {
    			$this->_drm = $drm->generateSuivante($campagne, false);
    		} else {
		    	$this->_drm = new DRM();
		    	$this->_drm->identifiant = $this->getTiers()->identifiant;
		    	$this->_drm->campagne = $campagne;
    		}
       	}
        return $this->_drm;
    }

}
