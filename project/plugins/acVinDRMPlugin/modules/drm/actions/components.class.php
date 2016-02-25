<?php

class drmComponents extends sfComponents {

    public function executeFormEtablissementChoice() {
        if (!$this->identifiant) {
            $this->identifiant = null;
        }

        if (!$this->form) {
            $this->form = new DRMEtablissementChoiceForm('INTERPRO-declaration', array('identifiant' => $this->identifiant));
        }
    }

    public function executeDelete() {
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->drm = $this->getRoute()->getDRM();
        if ($request->isMethod(sfRequest::POST)) {
            if ($request->getParameter('confirm')) {
                $this->drm->delete();
            }

            $this->redirect('drm_etablissement', $this->drm);
        }
    }

    public function executeMonEspaceDrm() {
        if (!$this->calendrier)
            $this->calendrier = new DRMCalendrier($this->etablissement, $this->campagne, $this->isTeledeclarationMode);
        $this->lastDrmToCompleteAndToStart = $this->calendrier->getLastDrmToCompleteAndToStart();
        $this->hasNoPopupCreation = (isset($this->accueil_drm) && $this->accueil_drm);
        if (!$this->hasNoPopupCreation) {
            $this->creationDrmsForms = $this->getCreationDrmsForms();
        }
    }

    public function executeEtapes() {
        $this->config_certifications = $this->drm->getConfig()->declaration->certifications;
        $this->certifications = array();

        $i = 3;
        foreach ($this->config_certifications as $certification_config) {
            if ($this->drm->exist($certification_config->getHash())) {
                $certif = $this->drm->get($certification_config->getHash());
                if ($certif->hasMouvementCheck()) {
                    $this->certifications[$i] = $this->drm->get($certification_config->getHash());
                    $i++;
                }
            }
        }
        $nbCertifs = count($this->certifications);
        if (count($this->drm->getDetailsAvecVrac()) > 0) {
            $this->numeros = array(
                'informations' => 1,
                'ajouts_liquidations' => 2,
                'recapitulatif' => 3,
                'vrac' => 3 + $nbCertifs,
                'declaratif' => 4 + $nbCertifs,
                'validation' => 5 + $nbCertifs,
            );
        } else {
            $this->numeros = array(
                'informations' => 1,
                'ajouts_liquidations' => 2,
                'recapitulatif' => 3,
                'declaratif' => 3 + $nbCertifs,
                'validation' => 4 + $nbCertifs,
            );
        }

        $this->numero = $this->numeros[$this->etape];
        if (isset($this->numeros[$this->drm->etape]))
            $this->numero_autorise = $this->numeros[$this->drm->etape];
        else
            $this->numero_autorise = '';
        $this->numero_vrac = (isset($this->numeros['vrac'])) ? $this->numeros['vrac'] : null;
        $this->numero_declaratif = $this->numeros['declaratif'];
        $this->numero_validation = $this->numeros['validation'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification_config) {
                if ($this->drm->exist($certification_config->getHash())) {
                    if ($this->certification == $certification_config->getKey()) {
                        break;
                    }
                    $this->numero++;
                }
            }
        }
    }

    public function executeHistoriqueItem() {
        $this->periode_version = DRMClient::getInstance()->buildPeriodeAndVersion($this->drm[DRMHistorique::VIEW_PERIODE], $this->drm[DRMHistorique::VIEW_INDEX_VERSION]);
        $this->etablissement_identifiant = $this->drm[DRMHistorique::VIEW_INDEX_ETABLISSEMENT];
        $this->valide = $this->drm[DRMHistorique::VIEW_INDEX_STATUS] && $this->drm[DRMHistorique::VIEW_INDEX_STATUS] > 0;
        $this->titre = $this->drm[DRMHistorique::VIEW_PERIODE];
        if ($this->drm[DRMHistorique::VIEW_INDEX_VERSION]) {
            $this->titre .= ' ' . $this->drm[DRMHistorique::VIEW_INDEX_VERSION];
        }
        $this->derniere = $this->drm[DRMHistorique::DERNIERE];
        $this->drm = DRMClient::getInstance()->find($this->drm[7]);
    }

    public function executeHistoriqueList() {
        if (isset($this->limit)) {
            $this->list = $this->historique->getSliceDRMs($this->limit);
        } else {
            $this->list = $this->historique->getDRMsParCampagneCourante();
        }
        $this->futurDRM = current($this->historique->getFutureDRM());
        $this->hasNewDRM = false;
        if (DRMClient::getInstance()->getCurrentPeriode() >= ($this->futurDRM[DRMHistorique::VIEW_PERIODE]) && !$this->historique->hasDRMInProcess()) {
            $this->hasNewDRM = true;
            if (isset($this->limit)) {
                $this->limit--;
            }
        }
    }

    public function executeCalendrier() {
        if (!$this->calendrier)
            $this->calendrier = new DRMCalendrier($this->etablissement, $this->campagne, $this->isTeledeclarationMode);
        if ($this->isTeledeclarationMode) {
            $this->creationDrmsForms = $this->getCreationDrmsForms();
        }
    }

    public function executeStocks() {
        $this->calendrier = new DRMCalendrier($this->etablissement, $this->campagne);
        $this->produits = array();
        $this->vigilance = false;
        foreach ($this->calendrier->getPeriodes() as $periode) {
            $drm = $this->calendrier->getDRM($periode);
            if ($drm && $drm->isValidee()) {
                foreach ($drm->getProduitsDetails() as $produit) {
                    $d = new stdClass();
                    $d->version = $drm->version;
                    $d->periode = $periode;
                    $d->mois = ucfirst($this->calendrier->getPeriodeLibelle($periode));
                    $d->produit_hash = $produit->getHash();
                    $d->produit_libelle = $produit->getLibelle();
                    $d->stocks_debut_initial = $produit->stocks_debut->initial;
                    $d->stocks_debut_dont_revendique = $produit->stocks_debut->dont_revendique;
                    $d->total_entrees = $produit->total_entrees;
                    $d->total_sorties = $produit->total_sorties;
                    $d->total_entrees_revendique = $produit->total_entrees_revendique;
                    $d->total_sorties_revendique = $produit->total_sorties_revendique;
                    $d->stocks_fin_final = $produit->stocks_fin->final;
                    $d->stocks_fin_dont_revendique = $produit->stocks_fin->dont_revendique;
                    $d->total_facturable = $produit->total_facturable;
                    $this->produits[] = $d;
                }
            } elseif ($drm && !$drm->isValidee()) {
                $this->vigilance = true;
            }
        }
    }

    public function executeStocksRecap() {
        $this->recaps = array();

        $drms = DRMStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
        $this->periode_debut = '';
        $conf = ConfigurationClient::getConfigurationByCampagne($this->campagne);
        $campgne = '999999';
        foreach ($drms as $drm) {
            if (!$conf->get($drm->produit_hash)->getCepage()->isCVOActif($drm->periode))
                continue;
            if (!isset($this->recaps[$drm->produit_hash])) {
                $this->recaps[$drm->produit_hash] = $this->initLigneRecap($conf, $drm->produit_hash);
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
        if (isset($drm)) {
            $this->periode_fin = ConfigurationClient::getInstance()->getPeriodeLibelle($drm->periode);
        }

        try {
            $revs = RevendicationStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, $this->etablissement->identifiant);
            foreach ($revs as $rev) {
                if (!isset($this->recaps[$rev->produit_hash])) {
                    $this->recaps[$rev->produit_hash] = $this->initLigneRecap($conf, $rev->produit_hash);
                }
                $this->recaps[$rev->produit_hash]['volume_revendique_drev'] += $rev->volume;
            }
        } catch (Exception $e) {
            
        }

        $dss = DSStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
        foreach ($dss as $ds) {
            if (!isset($this->recaps[$ds->produit_hash])) {
                $this->recaps[$ds->produit_hash] = $this->initLigneRecap($conf, $ds->produit_hash);
            }
            $this->recaps[$ds->produit_hash]['volume_stock_fin_ds'] = $ds->volume;
        }

        $dss = DSStocksView::getInstance()->findByCampagneAndEtablissement(ConfigurationClient::getInstance()->getPreviousCampagne($this->campagne), null, $this->etablissement->identifiant);
        foreach ($dss as $ds) {
            if (!isset($this->recaps[$ds->produit_hash])) {
                $this->recaps[$ds->produit_hash] = $this->initLigneRecap($conf, $ds->produit_hash);
            }
            $this->recaps[$ds->produit_hash]['volume_stock_debut_ds'] = $ds->volume;
        }

        $contrats = VracStocksView::getInstance()->findVinByCampagneAndEtablissement($this->campagne, $this->etablissement);
        foreach ($contrats as $hash_produit => $volume) {
            if (!isset($this->recaps[$hash_produit])) {
                $this->recaps[$hash_produit] = $this->initLigneRecap($conf, $hash_produit);
            }
            $this->recaps[$hash_produit]['volume_stock_commercialisable'] = $this->recaps[$hash_produit]['volume_stock_fin'] - $volume;
        }
    }

    protected function initLigneRecap($conf, $produit_hash) {
        $ligne = array();
        $ligne['produit'] = $conf->get($produit_hash)->getLibelleFormat();
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

    private function getCreationDrmsForms() {
        $this->drmsToCreateForms = array();
        $this->drmsToCreate = $this->calendrier->getDrmsToCreateArray();
        foreach ($this->drmsToCreate as $identifiantEtb => $periodeArray) {
            foreach ($periodeArray as $periode => $bool) {
                $this->drmsToCreateForms[$identifiantEtb . '_' . $periode] = new DRMChoixCreationForm(array(), array('identifiant' => $identifiantEtb, 'periode' => $periode));
            }
        }
    }

}
