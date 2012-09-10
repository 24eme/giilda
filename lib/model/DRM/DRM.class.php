<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';

    protected $mother = null;

    public function constructId() {

        $this->set('_id', DRMClient::getInstance()->buildId($this->identifiant, 
                                                            $this->periode, 
                                                            $this->version));
    }

    public function getPeriodeAndVersion() {

        return DRMClient::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function getMois() {
        
        return DRMClient::getInstance()->getMois($this->periode);
    }

    public function getAnnee() {
        
        return DRMClient::getInstance()->getAnnee($this->periode);
    }

    public function getDate() {
        
        return sprintf('%4d-%2d-%2d', $this->getAnnee(), $this->getMois(), date("t",$this->getMois()));
    }

    public function setPeriode($periode) {
        $this->campagne = DRMClient::getInstance()->buildCampagne($periode);

        return $this->_set('periode', $periode);
    }

    public function getRectificative() {
        if (preg_match('/^R([0-9]{2})/', $this->version, $matches)) {

            return (int) $matches[1];
        }

        return 0;
    }

    public function hasVersion() {

        return $this->isRectificative() || $this->isModificative();
    }

    public function isRectificative() {

        return $this->getRectificative() > 0;
    }

    public function getModificative() {
        if (preg_match('/M([0-9]{2})$/', $this->version, $matches)) {

            return (int) $matches[1];
        }

        return 0;
    }

    public function isModificative() {

        return $this->getModificative() > 0;
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
        if($this->declarant->siege->code_postal )  {
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
    
   public function getVracs() {
        $vracs = array();
        foreach ($this->getDetails() as $d) {
        if ($vrac = $d->sorties->vrac_details)
            $vracs[] = $vrac;
        }
        
        return $vracs;
    }

    public function generateSuivante($periode, $keepStock = true) 
    {
        $drm_suivante = clone $this;
    	$drm_suivante->init(array('keepStock' => $keepStock));
        $drm_suivante->update();
        $drm_suivante->periode = $periode;
	    $drm_suivante->precedente = $this->_id;
        $drm_suivante->devalide();
       
    	foreach ($drm_suivante->getDetails() as $detail) {
    	   $drm_suivante->get($detail->getHash())->remove('vrac');
    	}

        return $drm_suivante;
    }

    public function init($params = array()) {
      	parent::init($params);

        $this->remove('douane');
        $this->add('douane');
        $this->remove('declarant');
        $this->add('declarant');
        $this->version = null;
        $this->raison_rectificative = null;
        $this->etape = null;
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

    public function getMaster() {

        return DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);
    }

    public function isMaster() {

        return $this->getMaster()->get('_id') == $this->get('_id');
    }

    public function isRectifiable() {
        return false;

        return $this->isVersionnable();
    }

    public function isModifiable() {

        return $this->isVersionnable();
    }

    protected function isVersionnable() {
        if (!$this->isValidee()) {
           
           return false;
        }

        return $this->isMaster();
    }

    public function needNextVersion() {
        if($this->isModificative()) {

            return $this->needNextModificative();
        }

        if($this->isRectificative()) {

            return $this->needNextRectificative();
        }

        return false;      
    }

    public function needNextRectificative() {
        if (!$this->isRectificative()) {
	       
           return false;
        }

        return $this->motherHasChanged();
    }

    public function needNextModificative() {
        if (!$this->isModificative()) {
           
           return false;
        }

       return $this->motherHasChanged();
    }


    protected function motherHasChanged() {
        if ($this->declaration->total != $this->getMother()->declaration->total) {
           
           return true;
        }

        if (count($this->getDetails()) != count($this->getMother()->getDetails())) {
           
           return true;
        }

        if ($this->droits->douane->getCumul() != $this->getMother()->droits->douane->getCumul()) {
           
           return true;
        }

        return false;
    }

    public function generateRectificative() {
        $drm_rectificative = clone $this;

        if(!$this->isRectifiable()) {

            throw new sfException('This DRM is not rectificable, maybe she was already rectificate');
        }

        $drm_rectificative->version = DRMClient::getInstance()->buildVersion($this->getRectificative() + 1, 0);
        $drm_rectificative->devalide();

        return $drm_rectificative;
    }

    public function generateModificative() {
        $drm_modificative = clone $this;

        if(!$this->isModifiable()) {

            throw new sfException('This DRM is not modifiable, maybe she was already rectificate');
        }

        $drm_modificative->version = DRMClient::getInstance()->buildVersion($this->getRectificative(), $this->getModificative() + 1);
        $drm_modificative->devalide();

        return $drm_modificative;
    }

    public function getPrecedente() {
        if ($this->exist('precedente') && $this->_get('precedente')) {
	        
            return DRMClient::getInstance()->find($this->_get('precedente'));
        } else {
            
            return new DRM();
        }
    }

    public function getSuivante() {
       $periode = DRMClient::getInstance()->getPeriodeSuivante($this->periode);

       $next_drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode);
       if (!$next_drm) {

           return null;
       }
       
       return $next_drm;
    }

    public function generateNextVersion()
    {
        if($this->isModificative()) {

            return $this->generateModificativeSuivante();
        }

        if($this->isRectificative()) {

            return $this->generateRectificativeSuivante();
        }

        return false;
    }

    public function generateRectificativeSuivante() {
        if (!$this->isRectificative()) {

            throw new sfException('This drm is not a rectificative');
        }

        $next_drm = $this->getSuivante();

        if(!$next_drm) {

            return null;
        }
        
        $next_drm_rectificative = $next_drm->generateRectificative();
        $this->replicate($next_drm_rectificative);
        $next_drm_rectificative->update();
        $next_drm_rectificative->valide();

        return $next_drm_rectificative;
    }

    public function generateModificativeSuivante() {
        if (!$this->isModificative()) {

            throw new sfException('This drm is not a modificative');
        }

        $next_drm = $this->getSuivante();

        if(!$next_drm) {

            return null;
        }
        
        $next_drm_modificative = $next_drm->generateModificative();
        $this->replicate($next_drm_modificative);
        $next_drm_modificative->update();
        $next_drm_modificative->valide();

        return $next_drm_modificative;
    }

    protected function replicate($drm) {
        foreach($this->getDiffWithMother() as $key => $value) {
            $this->replicateDetail($drm, $key, $value, 'total', 'total_debut_mois');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/revendique', 'stocks_debut/warrante');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/bloque', 'stocks_debut/bloque');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/instance', 'stocks_debut/instance');
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

    public function getPreviousVersion() {
        if($this->isModificative()) {
            
            return DRMClient::getInstance()->buildVersion($this->getRectificative(), $this->getModificative() - 1);
        }

        if($this->isRectificative()) {

            return DRMClient::getInstance()->getMasterVersionOfRectificative($this->identifiant, 
                                                                 $this->periode, 
                                                                 DRMClient::getInstance()->buildVersion($this->getRectificative() - 1, 0));
        }

        return null;
    }

    public function motherGet($hash) {

        return $this->getMother()->get($hash);
    }

    public function motherExist($hash) {

        return $this->getMother()->exist($hash);
    }

    public function getMother() {
        if (!$this->hasVersion()) {

            throw new sfException("You can't get the mother of a non version drm");
        }

        if(is_null($this->mother)) {
            $this->mother = DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($this->identifiant, $this->periode, $this->getPreviousVersion()));
        }

        return $this->mother;    
    }

    public function getDiffWithMother() {

        return $this->store('diff_with_master_drm', array($this, 'getDiffWithMotherAbstract'));
    }

    public function isModifiedMother($hash_or_object, $key = null) {
        if(!$this->hasVersion()) {

            return false;
        }
        $hash = ($hash_or_object instanceof acCouchdbJson) ? $hash_or_object->getHash() : $hash_or_object;
        $hash .= ($key) ? "/".$key : null;

        return array_key_exists($hash, $this->getDiffWithMother());
    }

    public function devalide() {
      $this->etape = null;
      $this->clearMouvements();
      $this->valide->identifiant = '';
      $this->valide->date_saisie = '';
      $this->valide->date_signee = '';
    }

    public function isValidee() {

        return ($this->valide->date_saisie);
    }

    public function validate($options = null) {
        $this->storeIdentifiant($options);
        $this->storeDates();

        if (!isset($options['no_droits']) || !$options['no_droits']) {
           //$this->setDroits();
        }
        
        $this->setInterpros();        
        $this->generateMouvements();
        $this->updateVracs();
    }

    public function storeIdentifiant($options) {
        $identifiant = $this->identifiant;

        if ($options && is_array($options)) {
            if (isset($options['identifiant']))
                $identifiant = $options['identifiant'];
        }

        $this->valide->identifiant = $identifiant;
    }

    public function storeDates() {
        if (!$this->valide->date_saisie) {
           $this->valide->add('date_saisie', date('c'));
        }

        if (!$this->valide->date_signee) {
           $this->valide->add('date_signee', date('c'));
        }

    }

    public function updateVracs() {        
        foreach ($this->getDetails() as $d) {            
            foreach ($d->sorties->vrac_details as $vrac_detail) {                
                $vrac = VracClient::getInstance()->find($vrac_detail->identifiant);
                $vrac->enleverVolume($vrac_detail->volume);
                $vrac->save();
            }          
        }     
    }


    public function setInterpros() {
      $i = $this->getInterpro();
      if ($i)
       $this->interpros->add(0,$i->getKey());
    }

    public function save() {
        if (!preg_match('/^2\d{3}-[01][0-9]$/', $this->periode)) {
            throw new sfException('Wrong format for periode ('.$this->periode.')');
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

    protected function getDiffWithMotherAbstract() {

        return $this->getDiffWithAnotherDRM($this->getMother()->getData());
    }

    protected function getDRMHistoriqueAbstract() {
        
        return new DRMHistorique($this->identifiant, $this->periode);
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
        $configurationDroits = $appellation->getConfig()->interpro->get($this->getInterpro()->get('_id'))->droits->get($type)->getCurrentDroit($this->periode);
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
	   
       return strftime('%B %Y', strtotime($this->periode.'-01'));
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
    	
        return $this->declaratif->hasApurementPossible();
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
    	if (!$this->exist('valide')) {
    		
            return false;
        }

    	if (!$this->valide->exist('status')) {
    		
            return false;
        }

    	if ($this->valide->status != DRMClient::VALIDE_STATUS_VALIDEE_ENVOYEE && $this->valide->status != DRMClient::VALIDE_STATUS_VALIDEE_RECUE) {
    		
            return false;
    	}
    		
        return true;
    }
    /*
     * Pour les users administrateur
     */
    public function canSetStockDebutMois() {
    	if ($this->getPrecedente()->isNew()) {
    		
            return true;
    	}

        if ($this->isDebutCampagne()) {
    		
            return true;
    	}
    		
        return false;
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

    public function getMouvementsCalcule() {
        
        return $this->declaration->getMouvements();
    }

    public function getMouvementsCalculeByIdentifiant($identifiant) {
        $mouvements = $this->getMouvementsCalcule();

        return isset($mouvements[$identifiant]) ? $mouvements[$identifiant] : array();
    }

    public function clearMouvements() {
        $this->remove('mouvements');
        $this->add('mouvements');
    }
    
    public function generateMouvements() {
        $this->clearMouvements();
        $this->mouvements = $this->getMouvementsCalcule();
    }
    
    public function findMouvement($cle_mouvement){
         return $this->mouvements[$cle_mouvement];
    }
}
