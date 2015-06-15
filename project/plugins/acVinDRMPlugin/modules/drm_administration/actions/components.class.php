<?php

class drm_administrationComponents extends sfComponents {

  public function executeAdministration() {
      $this->drm->initReleveNonAppurement();
      $this->administrationForm = new  DRMAdministrationForm($this->drm);
      if ($this->requestAdministration->isMethod(sfRequest::POST)) {
            $this->administrationForm->bind($this->requestAdministration->getParameter($this->administrationForm->getName()));
            if ($this->administrationForm->isValid()) {
                $this->administrationForm->save();
                $this->generateUrl('drm_validation', $this->administrationForm->getObject());
            }
        }
  }

    public function executeStocks() {
        $this->calendrier = new DRMCalendrier($this->etablissement->identifiant, $this->campagne);
        $this->produits = array();
        $this->vigilance = false;
        foreach($this->calendrier->getPeriodes() as $periode) {
            $drm = $this->calendrier->getDRM($periode);
            if($drm && $drm->isValidee()) {
                foreach($drm->getProduits() as $produit) {
                    $d = new stdClass();
                    $d->version = $drm->version;
                    $d->periode = $periode;
                    $d->mois = ucfirst($this->calendrier->getPeriodeLibelle($periode));
                    $d->produit_hash = $produit->getHash();
                    $d->produit_libelle = $produit->getLibelle();
                    $d->total_debut_mois = $produit->total_debut_mois;
                    $d->total_entrees = $produit->total_entrees;
                    $d->total_sorties = $produit->total_sorties;
                    $d->total = $produit->total;
                    $d->total_facturable = $produit->total_facturable;
                    $this->produits[] = $d;
                } 
            } elseif($drm && !$drm->isValidee()) {
                $this->vigilance = true;
            }      
        }
    }

    public function executeStocksRecap() {
        $this->recaps = array();

        $drms = DRMStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
	$this->periode_debut = '';
	$campgne = '999999';
        foreach($drms as $drm) {
            if (!isset($this->recaps[$drm->produit_hash])) {
                $this->recaps[$drm->produit_hash] = $this->initLigneRecap($drm->produit_hash);
                $this->recaps[$drm->produit_hash]['volume_stock_debut'] = $drm->volume_stock_debut_mois;
		if ($campgne > $this->periode_debut) {
		        $this->periode_debut = ConfigurationClient::getInstance()->getPeriodeLibelle($drm->periode);
		}
            }
            
            $this->recaps[$drm->produit_hash]['volume_entrees'] += $drm->volume_entrees;
            $this->recaps[$drm->produit_hash]['volume_sorties'] += $drm->volume_sorties;
            $this->recaps[$drm->produit_hash]['volume_facturable'] += $drm->volume_facturable;
            $this->recaps[$drm->produit_hash]['volume_recolte'] += $drm->volume_recolte;
            $this->recaps[$drm->produit_hash]['volume_stock_fin'] = $drm->volume_stock_fin_mois;
            $this->recaps[$drm->produit_hash]['volume_stock_commercialisable'] = $this->recaps[$drm->produit_hash]['volume_stock_fin'];  
        }

        $this->periode_fin = '';
        if(isset($drm)) {
    	    $this->periode_fin = ConfigurationClient::getInstance()->getPeriodeLibelle($drm->periode);
        }
        
        $revs = RevendicationStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, $this->etablissement->identifiant);
        foreach($revs as $rev) {
            if (!isset($this->recaps[$rev->produit_hash])) {
                $this->recaps[$rev->produit_hash] = $this->initLigneRecap($rev->produit_hash);
            }
            $this->recaps[$rev->produit_hash]['volume_revendique_drev'] += $rev->volume;
        }

        $dss = DSStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
        foreach($dss as $ds) {
            if (!isset($this->recaps[$ds->produit_hash])) {
                $this->recaps[$ds->produit_hash] = $this->initLigneRecap($ds->produit_hash);
            }
            $this->recaps[$ds->produit_hash]['volume_stock_fin_ds'] = $ds->volume;
        }

        $dss = DSStocksView::getInstance()->findByCampagneAndEtablissement(ConfigurationClient::getInstance()->getPreviousCampagne($this->campagne), null, $this->etablissement->identifiant);
        foreach($dss as $ds) {
            if (!isset($this->recaps[$ds->produit_hash])) {
                $this->recaps[$ds->produit_hash] = $this->initLigneRecap($ds->produit_hash);
            }
            $this->recaps[$ds->produit_hash]['volume_stock_debut_ds'] = $ds->volume;
        }

        $contrats = VracStocksView::getInstance()->findVinByCampagneAndEtablissement($this->campagne, $this->etablissement);
        foreach($contrats as $hash_produit => $volume) {
            if (!isset($this->recaps[$hash_produit])) {
                $this->recaps[$hash_produit] = $this->initLigneRecap($hash_produit);
            }
            $this->recaps[$hash_produit]['volume_stock_commercialisable'] = $this->recaps[$hash_produit]['volume_stock_fin'] - $volume;
        }
    }

    protected function initLigneRecap($produit_hash)  {
        $ligne = array();
        $ligne['produit'] = ConfigurationClient::getCurrent()->get($produit_hash)->getLibelleFormat();
        $ligne['volume_stock_debut'] = 0;
        $ligne['volume_stock_debut_ds'] = null;
        $ligne['volume_recolte'] = 0;
        $ligne['volume_revendique_drev'] = null;
        $ligne['volume_entrees'] = 0;
        $ligne['volume_sorties'] = 0;
        $ligne['volume_facturable'] = 0;
        $ligne['volume_stock_commercialisable'] = 0;
        $ligne['volume_stock_fin'] = 0;
        $ligne['volume_stock_fin_ds'] = null;

        return $ligne;
    }
}
