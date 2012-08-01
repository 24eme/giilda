<?php
class DRMHistorique
{
	const VIEW_INDEX_ETABLISSEMENT = 0;
	const VIEW_INDEX_ANNEE = 1;
	const VIEW_INDEX_MOIS = 2;
	const VIEW_INDEX_RECTIFICATIVE = 3;
	const VIEW_INDEX_STATUS = 4;
	const VIEW_INDEX_STATUS_DOUANE_ENVOI = 5;
	const VIEW_INDEX_STATUS_DOUANE_ACCUSE = 6;
	const DERNIERE = 'derniere';
	const CAMPAGNE = 'campagne';
    const REGEXP_CAMPAGNE = '#^[0-9]{4}-[0-9]{2}$#';

	public $etablissement;
	private $campagneCourante;
	private $drms;
	private $campagnes;
	
	public function __construct($etablissement, $campagneCourante = null)
	{
		$this->etablissement = $etablissement;
		$this->campagneCourante = $campagneCourante;
	}
	
	public function getSliceDRMs($limit = 0) {
		return array_slice($this->getDRMs(), 0, $limit);
	}
	
	public function getDRMs($limite = 0)
	{
		if (!$this->drms) {
			$this->loadDRMs();
		}
		return $this->drms;
	}
	
	private function loadDRMs()
	{
		$drms = acCouchdbManager::getClient()
						->startkey(array($this->etablissement, null))
    					->endkey(array($this->etablissement, array()))
    					->reduce(false)
    					->getView("drm", "all")
    					->rows;

		$result = array();
		foreach ($drms as $drm) {
		  $result[$drm->id] = $drm->key;
		}
		krsort($result);

		$campagne = null;
		foreach($result as $key => $item) {
			$result[$key][self::CAMPAGNE] = $this->makeCampagne($item[self::VIEW_INDEX_ANNEE], $item[self::VIEW_INDEX_MOIS]);
			if ($item[self::VIEW_INDEX_ANNEE].'-'.$item[self::VIEW_INDEX_MOIS] != $campagne) {
				$result[$key][self::DERNIERE] = true;
				$campagne = $item[self::VIEW_INDEX_ANNEE].'-'.$item[self::VIEW_INDEX_MOIS];
			} else {
				$result[$key][self::DERNIERE] = false;
			}
		}
		$this->drms = $result;
	}
	
	public function getCampagnes()
	{
		if (!$this->campagnes) {
			$campagnes = array();
			$drms = $this->getDRMs();
	    	foreach ($drms as $drm) {
		  	if (!in_array($drm[self::CAMPAGNE], $campagnes)) {
	  				$campagnes[] = $drm[self::CAMPAGNE];
	    		}
	  		}
	  		rsort($campagnes);
	  		$this->campagnes = $campagnes;
		}
		return $this->campagnes;
	}
	
	private function makeCampagne($annee, $mois) {
		if ($annee.$mois < $annee.'08') {
			return ($annee-1).'-'.$annee;
		} else {
			return $annee.'-'.($annee+1);
		}
	}
	
	public function getDRMsParCampagneCourante()
	{
		$drmsCampagne = array();
		$campagneCourante = $this->getCampagneCourante();
		$drms = $this->getDRMs();
		foreach ($drms as $id => $drm) {
			if ($drm[self::CAMPAGNE] == $campagneCourante) {
				$drmsCampagne[$id] = $drm;
			}
		}
		return $drmsCampagne;
	}
	
	public function getCampagneCourante()
	{
		if (!$this->campagneCourante) {
			if($campagnes = $this->getCampagnes()) {
				$this->campagneCourante = $campagnes[0];
			}
		}
		return $this->campagneCourante;
	}
	
	public function getFutureDRM()
	{
		$lastDRM = current($this->getLastDRM());
		if (!$lastDRM) {
		  return array('DRM-'.$this->etablissement.'-'.date('Y').'-'.date('m') => array($this->etablissement, date('Y'), sprintf("%02d", date('m')), 0, null, null));
		}
		$nextMonth = $lastDRM[self::VIEW_INDEX_MOIS] + 1;
		$nextYear = $lastDRM[self::VIEW_INDEX_ANNEE];
		if ($nextMonth > 12) {
			$nextMonth = '01';
			$nextYear++;
		}
	        $nextMonth = sprintf("%02d", $nextMonth);
		return array('DRM-'.$this->etablissement.'-'.$nextYear.'-'.$nextMonth => array($this->etablissement, $nextYear, $nextMonth, 0, null, null));
	}

	public function getLastDRM()
	{
		return $this->getSliceDRMs(1);
	}
	
	public function hasDRMInProcess()
	{
		$result = false;
		$drms = $this->getDRMs();
		foreach ($drms as $drm) {
			if (!$drm[self::VIEW_INDEX_STATUS]) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	public function getEtablissementIdentifiant() {
	  return $this->etablissement;
	}

    public function getNextByCampagne($campagne)
    {
        $drms = $this->getDRMs();
        $dateCampagne = $this->getDateObjectByCampagne($campagne);
        $nextDrm = null;
        foreach ($drms as $drm) {
            if ($drm[self::VIEW_INDEX_ANNEE].$drm[self::VIEW_INDEX_MOIS] <= $dateCampagne->format('Ym') && is_null($drm[self::VIEW_INDEX_RECTIFICATIVE])) {
                break;
            } elseif (is_null($drm[self::VIEW_INDEX_RECTIFICATIVE])) {
                $nextDrm = $drm;
            }
        }
        return $nextDrm;
    }
    
    public function getPrevByCampagne($campagne)
    {
        $drms = $this->getDRMs();
        $dateCampagne = $this->getDateObjectByCampagne($campagne);
        $prevDrm = null;
        foreach ($drms as $drm) {
            if ($drm[self::VIEW_INDEX_ANNEE].$drm[self::VIEW_INDEX_MOIS] < $dateCampagne->format('Ym') && is_null($drm[self::VIEW_INDEX_RECTIFICATIVE])) {
                $prevDrm = $drm;
                break;
            }
        }
        return $prevDrm;
    }
    
    public function getDateObjectByCampagne($campagne)
    {
        $this->checkCampagneFormat($campagne);
        $campagneTab = explode('-', $campagne);
        return new DateTime($campagneTab[0].'-'.$campagneTab[1].'-01');
    }
    
    public function checkCampagneFormat($campagne)
    {
        if (!preg_match(self::REGEXP_CAMPAGNE, $campagne)) {
            throw new sfException('La campagne doit être au format AAAA-MM');
        }
    }
}