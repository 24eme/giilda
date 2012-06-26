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

}
