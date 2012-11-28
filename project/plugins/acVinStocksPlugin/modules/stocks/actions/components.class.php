<?php

class stocksComponents extends sfComponents {

    public function executeChooseEtablissement() {
        if (!$this->form) {
          $this->form = new StocksEtablissementChoiceForm('INTERPRO-inter-loire',array('identifiant' => $this->identifiant));
        }
    }

    public function executeRecap() {

        $this->recaps = array();

        $drms = DRMStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
        foreach($drms as $drm) {
            if (!isset($this->recaps[$drm->produit_hash])) {
                $this->recaps[$drm->produit_hash]['produit'] = ConfigurationClient::getCurrent()->get($drm->produit_hash)->getLibelleFormat();
                $this->recaps[$drm->produit_hash]['volume_stock_debut'] = $drm->volume_stock_debut_mois;
                $this->recaps[$drm->produit_hash]['volume_stock_debut_ds'] = null;
                $this->recaps[$drm->produit_hash]['volume_entrees'] = 0;
                $this->recaps[$drm->produit_hash]['volume_sorties'] = 0;
                $this->recaps[$drm->produit_hash]['volume_revendique'] = 0;
                $this->recaps[$drm->produit_hash]['volume_revendique_drev'] = null;
                $this->recaps[$drm->produit_hash]['volume_stock_fin'] = 0;
                $this->recaps[$drm->produit_hash]['volume_stock_fin_ds'] = null;
            }
            
            $this->recaps[$drm->produit_hash]['volume_entrees'] += $drm->volume_entrees;
            $this->recaps[$drm->produit_hash]['volume_sorties'] += $drm->volume_sorties;
            $this->recaps[$drm->produit_hash]['volume_revendique'] += $drm->volume_revendique;
            $this->recaps[$drm->produit_hash]['volume_stock_fin'] = $drm->volume_stock_fin_mois;  
        }

        $revs = RevendicationStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
        foreach($revs as $rev) {
            $this->recaps[$ds->produit_hash]['volume_revendique_drev'] = $rev->volume;
        }

        $dss = DSStocksView::getInstance()->findByCampagneAndEtablissement($this->campagne, null, $this->etablissement->identifiant);
        foreach($dss as $ds) {
            $this->recaps[$ds->produit_hash]['volume_stock_fin_ds'] = $ds->volume;
        }

        $dss = DSStocksView::getInstance()->findByCampagneAndEtablissement(ConfigurationClient::getInstance()->getPreviousCampagne($this->campagne), null, $this->etablissement->identifiant);
        foreach($dss as $ds) {
            $this->recaps[$ds->produit_hash]['volume_stock_debut_ds'] = $ds->volume;
        }


    }

    public function executeMouvements() {
        $this->mouvements = array_merge(
            DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne),
            SV12MouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne)
            );
    }

}
