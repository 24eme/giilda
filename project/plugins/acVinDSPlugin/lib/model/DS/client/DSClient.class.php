<?php

class DSClient extends acCouchdbClient {

	const TYPE_MODEL = 'DS';

    public static function getInstance()
    {
      return acCouchdbManager::getClient("DS");
    }

		public static function makeId($identifiant, $date, $version = null)
		{
			$id = self::TYPE_MODEL.'-' . $identifiant . '-' . str_replace('-', '', $date);
			if ($version) {
				$id .= '-' . $version;
			}
			return $id;
		}

		public function createOrFind($etablissement, $date = null, $teledeclare = false)
		{
			$dateDeclaration = self::getDateDeclaration($date);
			if ($ds = $this->find(self::makeId($etablissement, $dateDeclaration))) {
				return $ds;
			}
			$ds = new DS();
			$ds->initDoc($etablissement, $dateDeclaration, $teledeclare);
			return $ds;
		}

		public static function getDocumentRepriseProduits($identifiant, $dateDeclaration) {
				$periode = substr(str_replace('-', '', $dateDeclaration), 0, 6);
				$drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($identifiant, $periode);
				if (!$drm) {
					$drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($identifiant, substr($periode, 0, 4).'-'.substr($periode, -2));
				}
				return $drm;
		}

		public static function getDateDeclaration($date = null)
		{
			if (!$date) {
				$date = date('Y-m-d');
			} elseif (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
				throw new Exception('Date format invalide : '.$date);
			}
			$cm = new CampagneManager('08-01');
			$campagne = explode('-', $cm->getCampagneByDate($date));
			return $campagne[1].'-07-31';
		}

    public function findByArgs($identifiant, $date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$id = self::TYPE_MODEL.'-' . $identifiant . '-' . $date;
    	return $this->find($id, $hydrate);
    }

    public function findByIdentifiant($identifiant,  $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$view = $this->startkey(sprintf(self::TYPE_MODEL."-%s-%s", $identifiant, "00000000"))
    	->endkey(sprintf(self::TYPE_MODEL."-%s-%s", $identifiant, "99999999"));
    	return $view->execute($hydrate)->getDatas();
    }

    public function listPeriodesByEtablissementId($identifiant) {
    	$rows = $this->findByIdentifiant($identifiant, acCouchdbClient::HYDRATE_ON_DEMAND);
    	sfApplicationConfiguration::getActive()->loadHelpers(array('Date'));
    	$periodes = array();
    	foreach ($rows as $k => $v) {
    		$ex = explode('-', $k);
    		if (isset($ex[2])) {
    			$date = substr($ex[2], 0, 4).'-'.substr($ex[2], 4, 2).'-'.substr($ex[2], 6, 2);
    			if (!in_array($date, $periodes)) {
    				$periodes[$date] = ucfirst(format_date($date, 'MMMM yyyy', 'fr_FR'));
    			}
    		}
    	}
			$date = self::getDateDeclaration(date('Y-m-d'));
    	if (!in_array($date, $periodes)) {
    		$periodes[$date] = ucfirst(format_date($date, 'MMMM yyyy', 'fr_FR'));
    	}
    	krsort($periodes);
    	return $periodes;
    }


    public function findMasterByIdentifiantAndDate($identifiant, $date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $ds = $this->viewByIdentifiantDate($identifiant, str_replace('-', '', $date), $hydrate);
        foreach ($ds as $id => $doc) {
            return $doc;
        }
        return null;
    }

    protected function viewByIdentifiantDate($identifiant, $date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
	    	$rows = $this->findByIdentifiant($identifiant, $hydrate);
        $ds = array();
        foreach ($rows as $row) {
					if (strpos($row->_id, str_replace('-', '', $date)) === false) {
						continue;
					}
            $ds[$row->_id] = $row;
        }
        krsort($ds);
        return $ds;
    }

		public function findAll($hydrate = acCouchdbClient::HYDRATE_JSON) {
			$numeric = $this->startkey(sprintf(self::TYPE_MODEL."-%s", "0"))->endkey(sprintf(self::TYPE_MODEL."-%s", "9"))->execute($hydrate)->getDatas();
			$alpha = $this->startkey(sprintf(self::TYPE_MODEL."-%s", "A"))->endkey(sprintf(self::TYPE_MODEL."-%s", "Z"))->execute($hydrate)->getDatas();
			return array_merge($numeric, $alpha);
		}
}
