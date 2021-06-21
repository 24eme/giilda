<?php

class DSNegoceClient extends acCouchdbClient {

	const TYPE_MODEL = 'DSNEGOCE';

    public static function getInstance()
    {
      return acCouchdbManager::getClient("DSNegoce");
    }

		public function createOrFind($etablissement, $date = null)
		{
			$dsnegoce = new DSNegoce();
			$dsnegoce->initDoc($etablissement, $date);
			return ($exist = $this->find($dsnegoce->_id))? $exist : $dsnegoce;
		}

		public static function getDateFinCampagne($date)
		{
			$cm = new CampagneManager('08-01');
			$campagne = explode('-', $cm->getCampagneByDate($date));
			return $campagne[0].'-07-31';
		}

		public static function getDateDeclaration($date = null)
		{
			if (!$date) {
				$date = date('Y-m-d');
			} elseif (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
				throw new Exception('Date format invalide : '.$date);
			}
			return self::getDateFinCampagne($date);
		}

    public function findByArgs($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$id = self::TYPE_MODEL.'-' . $identifiant . '-' . $periode;
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
			$date = self::getDateFinCampagne(date('Y-m-d'));
    	if (!in_array($date, $periodes)) {
    		$periodes[$date] = ucfirst(format_date($date, 'MMMM yyyy', 'fr_FR'));
    	}
    	krsort($periodes);
    	return $periodes;
    }
}
