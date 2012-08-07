<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';
    const VALIDE_STATUS_EN_COURS = '';
    const VALIDE_STATUS_VALIDEE_ENATTENTE = 'VALIDEE';
    const VALIDE_STATUS_VALIDEE_ENVOYEE = 'ENVOYEE';
    const VALIDE_STATUS_VALIDEE_RECUE = 'RECUE';



    public function constructId() {
        $rectificative = ($this->exist('rectificative')) ? $this->rectificative : null;

        $this->set('_id', DRMClient::getInstance()->getId($this->identifiant, $this->campagne, $rectificative));
    }

    public function getCampagneAndRectificative() {
        $rectificative = ($this->exist('rectificative')) ? $this->rectificative : null;

        return DRMClient::getInstance()->getCampagneAndRectificative($this->campagne, $rectificative);
    }

    public function getProduit($hash, $labels = array()) {
        if (!$this->exist($hash)) {

            return false;
        }

        return $this->get($hash)->details->getProduit($labels);
    }

    public function addProduit($hash, $labels = array()) {
      if ($p = $this->getProduit($hash, $labels)) {
        return $p;
      }
      
      $detail = $this->getOrAdd($hash)->details->addProduit($labels);
      
      return $detail;
    }

    public function getDepartement() {
        if($this->declarant->siege->code_postal) {
          return substr($this->declarant->siege->code_postal, 0, 2);
        }

        return null;
    }

    public function getDetails() {
        
        return $this->declaration->getProduits();
    }

    public function getDetailsAvecVrac() {
      $details = array();
      foreach ($this->getDetails() as $d) {
	if ($d->sorties->vrac)
	  $details[] = $d;
      }
      return $details;
    }

    public function generateSuivante($campagne, $keepStock = true) 
    {
        $drm_suivante = clone $this;
    	$drm_suivante->init(array('keepStock' => $keepStock));
        $drm_suivante->update();
        $drm_suivante->campagne = $campagne;
	$drm_suivante->precedente = $this->_id;
        $drm_suivante->devalide();
       
	foreach ($drm_suivante->getDetails() as $detail) {
	  $drm_suivante->get($detail->getHash())->remove('vrac');
	}

        return $drm_suivante;
    }
    public function init($params = array()) {
      	parent::init($params);
      	$keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
		$this->remove('rectificative');
        $this->remove('douane');
        $this->add('douane');
        $this->remove('declarant');
        $this->add('declarant');
        $this->raison_rectificative = null;
        $this->etape = null;
        if (!$keepStock) {
        	$this->declaratif->adhesion_emcs_gamma = null;
        	$this->declaratif->paiement->douane->frequence = null;
        	$this->declaratif->paiement->douane->moyen = null;
        	$this->declaratif->paiement->cvo->frequence = null;
        	$this->declaratif->paiement->cvo->moyen = null;
        	$this->declaratif->caution->dispense = null;
        	$this->declaratif->caution->organisme = null;
        }
        $this->declaratif->defaut_apurement = null;
        $this->declaratif->daa->debut = null;
        $this->declaratif->daa->fin = null;
        $this->declaratif->dsa->debut = null;
        $this->declaratif->dsa->fin = null;
    }
    
    public function getNextCertification($currentCertification)
    {
    	$findCertification = false;
    	$nextCertification = null;
    	$config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
    	foreach ($config_certifications as $certification => $produit) {
            if ($this->produits->exist($certification)) {
            	if ($findCertification) {
            		$nextCertification = $this->declaration->certifications->get($certification);
            		break;
            	}
                if ($certification == $currentCertification->getKey()) {
                	$findCertification = true;
                }
            }
        }
        return $nextCertification;
    }

    public function setCampagneMoisAnnee($mois, $annee) {
      $this->campagne = sprintf("%04d-%02d", $annee, $mois);
    }

    public function setMois($mois) {
      $annee = $this->getAnnee();
      $this->setCampagneMoisAnnee($mois, $annee);
    }

    public function setAnnee($annee) {
      $mois = $this->getMois();
      $this->setCampagneMoisAnnee($mois, $annee);
    }

    public function getMois() {
        
        return preg_replace('/.*-/', '', $this->campagne)*1;
    }

    public function getAnnee() {
        
        return preg_replace('/-.*/', '', $this->campagne)*1;
    }

    public function getRectificative() {

        return $this->exist('rectificative') ? $this->_get('rectificative') : 0;
    }
    
    public function setDroits() {
        $this->remove('droits');
        $this->add('droits');
        foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->genres as $genre) {
    	        foreach ($genre->appellations as $appellation) {
                    $appellation->updateDroits($this->droits);
    	        }
            }
        }
    }

    public function getEtablissement() {
    	
        return EtablissementClient::getInstance()->find($this->identifiant);
    }
    
    public function getInterpro() {
    	
      	if ($this->getEtablissement()) {
         	
         	return $this->getEtablissement()->getInterproObject();
     	}
    }
    
    public function getDRMHistorique() {

        return $this->store('drm_historique', array($this, 'getDRMHistoriqueAbstract'));
    }

    public function isRectificative() {

        return $this->exist('rectificative') && $this->rectificative > 0;
    }

    public function isRectificable() {
        if (!$this->isValidee()) {
	       
           return false;
        }

        if ($drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->identifiant, $this->campagne, acCouchdbClient::HYDRATE_JSON)) {

            return $drm->_id == $this->get('_id');
        }

        return false;
    }

    public function needNextRectificative() {
      if (!$this->isRectificative()) {
	return false;
      }
      if ($this->declaration->total != $this->getDRMMaster()->declaration->total) {
	return true;
      }
      if (count($this->getDetails()) != count($this->getDRMMaster()->getDetails())) {
	return true;
      }
      if ($this->droits->douane->getCumul() != $this->getDRMMaster()->droits->douane->getCumul()) {
	return true;
      }
        return false;
    }

    public function generateRectificative() {
        $drm_rectificative = clone $this;

        if(!$this->isRectificable()) {

            throw new sfException('This DRM is not rectificable, maybe she was already rectificate');
        }

        if(!$drm_rectificative->exist('rectificative')) {
            $drm_rectificative->add('rectificative', 0);
        }

        $drm_rectificative->rectificative += 1;
	    $drm_rectificative->devalide();
        $drm_rectificative->etape = 'ajouts_liquidations';

        return $drm_rectificative;
    }

    public function getPrecedente() {
        if ($this->exist('precedente') && $this->_get('precedente')) {
	        
            return DRMClient::getInstance()->find($this->_get('precedente'));
        } else {
            
            return new DRM();
        }
    }

    public function getSuivante() {
       $date_campagne = new DateTime($this->getAnnee().'-'.$this->getMois().'-01');
       $date_campagne->modify('+1 month');
       $next_campagne = DRMClient::getInstance()->getCampagne($date_campagne->format('Y'), $date_campagne->format('m'));

       $next_drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->identifiant, $next_campagne);
       if (!$next_drm) {
           return null;
       }
       $next_drm->set('precedente', $this->get('_id'));

       return $next_drm;
    }

    public function generateRectificativeSuivante() {
        if (!$this->isRectificative()) {

            throw new sfException('This drm is not a rectificative');
        }

        $next_drm = $this->getSuivante();

        if ($next_drm) {
            $next_drm_rectificative = $next_drm->generateRectificative();
            $next_drm_rectificative->etape = 'validation';
            foreach($this->getDiffWithMasterDRM() as $key => $value) {
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'total', 'total_debut_mois');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/bloque', 'stocks_debut/bloque');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/warrante', 'stocks_debut/warrante');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/instance', 'stocks_debut/instance');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/commercialisable', 'stocks_debut/commercialisable');
            }
            $next_drm_rectificative->devalide();
            $next_drm_rectificative->update();

            return $next_drm_rectificative;
        } else {
            return null;
        }
    }

    protected function replicateDetail(&$drm, $key, $value, $hash_match, $hash_replication) {
        if (preg_match('|^(/declaration/certifications/.+/appellations/.+/mentions/.+/lieux/.+/couleurs/.+/cepages/.+/details/.+)/'.$hash_match.'$|', $key, $match)) {
            $detail = $this->get($match[1]);
            if (!$drm->exist($detail->getHash())) {
                $drm->addProduit($detail->getCepage()->getHash(), $detail->labels->toArray());
            }
            $drm->get($detail->getHash())->set($hash_replication, $value);
        }
    }

    public function getDRMMaster($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        if (!$this->isRectificative()) {

            throw new sfException("You can not recover the master of a non rectificative drm");
        }

        return DRMClient::getInstance()->findByIdentifiantCampagneAndRectificative($this->identifiant, $this->campagne, $this->rectificative - 1, $hydrate);    
    }

    public function getDiffWithMasterDRM() {

        return $this->store('diff_with_master_drm', array($this, 'getDiffWithMasterDRMAbstract'));
    }

    public function isModifiedMasterDRM($hash_or_object, $key = null) {
        if(!$this->isRectificative()) {

            return false;
        }
        $hash = ($hash_or_object instanceof acCouhdbJson) ? $hash_or_object->getHash() : $hash_or_object;
        $hash .= ($key) ? "/".$key : null;

        return array_key_exists($hash, $this->getDiffWithMasterDRM());
    }

    public function devalide() {
      $this->valide->identifiant = '';
      $this->valide->date_saisie = '';
      $this->valide->date_signee = '';
    }

    public function isValidee() {
      return ($this->valide->date_saisie);
    }

    public function validate($options = null) {
      $identifiant = null;
      if (count($options)) {
	if (isset($options['identifiant']))
	  $identifiant = $options['identifiant'];
      } 
      if (! $this->valide->date_saisie)
	$this->valide->add('date_saisie', date('c'));
      if (! $this->valide->date_signee)
	$this->valide->add('date_signee', date('c'));
      if (!$identifiant)
	$identifiant = $this->identifiant;
      $this->valide->identifiant = $identifiant;
      if (!isset($options['no_droits']) || !$options['no_droits'])
	$this->setDroits();
      $this->setInterpros();
    }

    public function setInterpros() {
      $i = $this->getInterpro();
      if ($i)
	$this->interpros->add(0,$i->getKey());
    }

    public function save() {
        if (!preg_match('/^2\d{3}-[01][0-9]$/', $this->campagne)) {
            throw new sfException('Wrong format for campagne ('.$this->campagne.')');
        }
        if ($user = $this->getUser()) {
        	if ($user->hasCredential(myUser::CREDENTIAL_ADMIN)) {
        		$compte = $user->getCompte();
        		$canInsertEditeur = true;
        		if ($lastEditeur = $this->getLastEditeur()) {
        			$diff = Date::diff($lastEditeur->date_modification, date('c'), 'i');
        			if ($diff < 25) {
        				$canInsertEditeur = false;
        			}
        		}
        		if ($canInsertEditeur) {
        			$this->addEditeur($compte);
        		}
        	}
        }

        return parent::save();
    }

    protected function getDiffWithAnotherDRM(stdClass $drm) {
        $other_json = new acCouchdbJsonNative($drm);
        $current_json = new acCouchdbJsonNative($this->getData());

        return $current_json->diff($other_json);
    }

    protected function getDiffWithMasterDRMAbstract() {
        $drm_master = $this->getDRMMaster(acCouchdbClient::HYDRATE_JSON)->getData();

        return $this->getDiffWithAnotherDRM($drm_master);
    }

    protected function getDRMHistoriqueAbstract() {
        return new DRMHistorique($this->identifiant, $this->campagne);
    }

    private function getTotalDroit($type) {
        $total = 0;
        foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
                $total += $appellation->get('total_'.$type);
            }
        }
        return $total;  
    }

    private function interpretHash($hash) {
      if (!preg_match('|declaration/certifications/([^/]*)/appellations/([^/]*)/|', $hash, $match)) {
        
        throw new sfException($hash." invalid");
      }
      
      return array('certification' => $match[1], 'appellation' => $match[2]);
    }

    private function setDroit($type, $appellation) {
        $configurationDroits = $appellation->getConfig()->interpro->get($this->getInterpro()->get('_id'))->droits->get($type)->getCurrentDroit($this->campagne);
        $droit = $appellation->droits->get($type);
        $droit->ratio = $configurationDroits->ratio;
        $droit->code = $configurationDroits->code;
        $droit->libelle = $configurationDroits->libelle;
    }
    
    public function isPaiementAnnualise() {
    	return $this->declaratif->paiement->douane->isAnnuelle();
    }

    public function getHumanDate() {
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	return strftime('%B %Y', strtotime($this->campagne.'-01'));
    }
    public function getEuValideDate() {
	return strftime('%d/%m/%Y', strtotime($this->valide->date_signee));
    }
    
    public function isDebutCampagne() {
    	return DRMPaiement::isDebutCampagne((int)$this->getMois());
    }
    public function getCurrentEtapeRouting() {
    	$etape = sfConfig::get('app_drm_etapes_'.$this->etape);
    	return $etape['url'];
    }
    public function setCurrentEtapeRouting($etape) {
    	if (!$this->isValidee()) {
    		$this->etape = $etape;
    		$this->getDocument()->save();
    	}
    }
    public function hasApurementPossible() {
    	if (
    		$this->declaratif->daa->debut ||
    		$this->declaratif->daa->fin ||
    		$this->declaratif->dsa->debut ||
    		$this->declaratif->dsa->debut ||
    		$this->declaratif->adhesion_emcs_gamma
    	) {
    		return true;
    	} else {
    		return false;
    	}
    }
    public function hasVrac() {
    	$detailsVrac = $this->getDetailsAvecVrac();
    	return (count($detailsVrac) > 0) ;
    }
    
    public function hasConditionneExport() {
      return ($this->declaration->getTotalByKey('sorties/export') > 0);
    }

    public function hasMouvementAuCoursDuMois() {
      return $this->hasVrac() || $this->hasConditionneExport();
    }

    public function isEnvoyee() {
    	if (!$this->exist('valide'))
    		return false;
    	if (!$this->valide->exist('status'))
    		return false;
    	if ($this->valide->status != self::VALIDE_STATUS_VALIDEE_ENVOYEE && $this->valide->status != self::VALIDE_STATUS_VALIDEE_RECUE) {
    		return false;
    	} else {
    		return true;
    	}
    }
    /*
     * Pour les users administrateur
     */
    public function canSetStockDebutMois() {
    	if ($this->getPrecedente()->isNew()) {
    		return true;
    	} elseif ($this->isDebutCampagne()) {
    		return true;
    	} else {
    		return false;
    	}
    }
    public function hasProduits() {
    	return (count($this->declaration->getProduits()) > 0)? true : false;
    }
    
    public function hasEditeurs() {
    	return (count($this->editeurs) > 0);
    }
    
    public function getLastEditeur() {
    	if ($this->hasEditeurs()) {
    		$editeurs = $this->editeurs->toArray();
    		return array_pop($editeurs);
    	} else {
    		return null;
    	}
    }
    
    public function getUser() {
    	return sfContext::getInstance()->getUser();
    }
    
    public function addEditeur($compte) {
    	$editeur = $this->editeurs->add();
    	$editeur->compte = $compte->_id;
    	$editeur->nom = $compte->nom;
    	$editeur->prenom = $compte->prenom;
    	$editeur->date_modification = date('c');
    }
    
}
