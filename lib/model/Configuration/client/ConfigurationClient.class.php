<?php

class ConfigurationClient extends acCouchdbClient {
	
	private static $current = array();

    protected $countries = null;

    const CAMPAGNE_DATE_DEBUT = '%s-08-01';
    const CAMPAGNE_DATE_FIN = '%s-07-31';

	/**
	*
	* @return CurrentClient 
	*/
	public static function getInstance() {

	  	return acCouchdbManager::getClient("CONFIGURATION");
	}

	/**
	*
	* @return Current 
	*/
	public static function getCurrent() {
		if (self::$current == null) {
		  self::$current = CacheFunction::cache('model', array(ConfigurationClient::getInstance(), 'retrieveCurrent'), array());
		}

		return self::$current;
	}
  
	/**
	*
	* @return Current
	*/
	public function retrieveCurrent() {
	  	$configuration = parent::retrieveDocumentById('CONFIGURATION');
	  	if (!sfConfig::get('sf_debug')) {
	    	$configuration->loadAllData();
	  	}

		return $configuration;
	}
  
    public function findProduitsForAdmin($interpro) {
        return $this->startkey(array($interpro))
              ->endkey(array($interpro, array()))->getView('configuration', 'produits_admin');
    }
  
    public function findProduitsByCertificationAndInterpro($interpro, $certif) {
        return $this->startkey(array($interpro, $certif))
              ->endkey(array($interpro, $certif, array()))->getView('configuration', 'produits_admin');
    }

    public function buildCampagne($date) {

        return sprintf('%s-%s', date('Y', strtotime($this->buildDateDebutCampagne($date))), date('Y', strtotime($this->buildDateFinCampagne($date))));
    }

    public function getDateDebutCampagne($campagne) {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return sprintf(self::CAMPAGNE_DATE_DEBUT, $annees[1]); 
    }

    public function getDateFinCampagne($campagne) {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return sprintf(self::CAMPAGNE_DATE_FIN, $annees[2]); 
    }

    public function buildDateDebutCampagne($date) {
        $annee = date('Y', strtotime($date));
        if(date('m', strtotime($date)) < 8) {
            $annee -= 1;
        }

        return sprintf(self::CAMPAGNE_DATE_DEBUT, $annee); 
    }

    public function buildDateFinCampagne($date) {

        return sprintf(self::CAMPAGNE_DATE_FIN, date('Y', strtotime($this->buildDateDebutCampagne($date)))+1);
    }

    public function getCurrentCampagne() {

        return $this->buildCampagne(date('Y-m-d'));
    }

    public function getPreviousCampagne($campagne) {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return sprintf('%s-%s', $annees[1]-1, $annees[2]-1); 

    }

    public function getNextCampagne($campagne) {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return sprintf('%s-%s', $annees[1]+1, $annees[2]+1); 

    }

    public function isDebutCampagne($periode) {

        return $this->getMois($periode) == 8;
    }

    public function getMois($periode) {
        
        return preg_replace('/([0-9]{4})([0-9]{2})/', '$2', $periode);
    }

    public function getAnnee($periode) {
        
        return preg_replace('/([0-9]{4})([0-9]{2})/', '$1', $periode);
    }

    public function getPeriodeLibelle($periode) {
      return $this->getMoisLibelle($periode).' '.$this->getAnnee($periode);
    }

    public function getMoisLibelle($periode) {
        $date = new sfDateFormat('fr_FR');

        if(!preg_match('/([0-9]{4})([0-9]{2})/', $periode, $matches)) {

            return null;
        }

        return $date->format(sprintf('%s-%s-%s', $matches[1], $matches[2], '01'), 'MMMM');
    }

    public function buildPeriode($annee, $mois) {

        return sprintf("%04d%02d", $annee, $mois);
    }

    public function buildDate($periode) {
        $lastDay = date('t',mktime(0, 0, 0, $this->getMois($periode), 1, $this->getAnnee($periode)));
        return sprintf('%4d-%02d-%02d', $this->getAnnee($periode), $this->getMois($periode), $lastDay);
    }

    public function getPeriodeDebut($campagne) {

        return date('Ym', strtotime(ConfigurationClient::getInstance()->getDateDebutCampagne($campagne)));
    }

    public function getPeriodeFin($campagne) {

        return date('Ym', strtotime(ConfigurationClient::getInstance()->getDateFinCampagne($campagne)));
    }

    public function getPeriodeSuivante($periode) {
        $nextMonth = $this->getMois($periode) + 1;
        $nextYear = $this->getAnnee($periode);

        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }
      
        return $this->buildPeriode($nextYear, $nextMonth);
    }

    public function getCurrentPeriode() {

        return date('Ym');
    }

    public function getCountryList() {
        if(is_null($this->countries)) {
            $destinationChoicesWidget = new sfWidgetFormI18nChoiceCountry(array('culture' => 'fr', 'add_empty' => true));
            $this->countries = $destinationChoicesWidget->getChoices();
            $this->countries['inconnu'] = 'Inconnu';
        }

        return $this->countries;
    }

    public function getCountry($code) {
        $countries = $this->getCountryList();

        return $countries[$code];
    }
  
}
