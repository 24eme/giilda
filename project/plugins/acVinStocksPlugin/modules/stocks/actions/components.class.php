<?php

class stocksComponents extends sfComponents {

    public function executeChooseEtablissement() {
        if (!$this->form) {
          $this->form = new StocksEtablissementChoiceForm('INTERPRO-inter-loire',array('identifiant' => $this->identifiant));
        }
    }

    public function executeMouvements() {
        $this->mouvements_viticulteur = $this->getMouvementsViticulteur();
        $this->mouvements_negociant = $this->getMouvementsNegociant();
    }

    public function executeRecapNegociant() {
        $mouvements = $this->getMouvementsNegociant();

        $this->recaps = array();

        $date = ConfigurationClient::getInstance()->getCampagneVinicole()->getDateDebutByCampagne($this->campagne);
        $conf = ConfigurationClient::getConfiguration($date);

        foreach($mouvements as $mouvement) {
            if (!$conf->get(preg_replace("|/details/.+$|", "", $mouvement->produit_hash))->getCepage()->isCVOActif($date)) {
                continue;
            }

            if (!array_key_exists($mouvement->produit_hash, $this->recaps)) {
                $this->recaps[$mouvement->produit_hash] = $this->initLigneRecapNegociant();
                $this->recaps[$mouvement->produit_hash]['produit'] = $mouvement->produit_libelle;
            }

            $volume = $mouvement->volume * -1;

            if($mouvement->type_hash == Mouvement::TYPE_HASH_CONTRAT_RAISIN) {
                $this->recaps[$mouvement->produit_hash]['volume_stock_raisin'] += $volume;
            }

            if($mouvement->type_hash == Mouvement::TYPE_HASH_CONTRAT_MOUT) {
                $this->recaps[$mouvement->produit_hash]['volume_stock_mout'] += $volume;
            }

            if($mouvement->type_hash == Mouvement::TYPE_HASH_CONTRAT_VRAC) {
                $this->recaps[$mouvement->produit_hash]['volume_stock_vin'] += $volume;
            }

            $this->recaps[$mouvement->produit_hash]['volume_stock_total'] += $volume;
        }
    }

    protected function initLigneRecapNegociant()  {
        $ligne = array();
        $ligne['volume_stock_raisin'] = 0;
        $ligne['volume_stock_mout'] = 0;
        $ligne['volume_stock_vin'] = 0;
        $ligne['volume_stock_total'] = 0;
        $ligne['volume_stock_ds'] = null;

        return $ligne;
    }

    protected function getMouvementsViticulteur() {
      $natifs_mvts_viti = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne);

      $sorted_mvts_viti = DRMClient::getInstance()->sortMouvementsForDRM($natifs_mvts_viti);
      return $this->sortMvtsByDrmId($sorted_mvts_viti);


    }

    protected function getMouvementsNegociant() {

        return SV12MouvementsConsultationView::getInstance()->getByIdentifiantAndCampagne($this->etablissement->identifiant, $this->campagne);
    }

    protected function sortMvtsByDrmId($mvts_viti){
      $sorted_mvts_viti_result = array();
      foreach ($mvts_viti as $type_drm => $sort_mvts_viti) {
        if(!array_key_exists($type_drm, $sorted_mvts_viti_result)){
          $sorted_mvts_viti_result[$type_drm] =   array();
        }
        foreach ($sort_mvts_viti as $keyMvts => $mvts) {
          foreach ($mvts as $keyMvt => $mvt) {
            if(!array_key_exists($mvt->doc_id, $sorted_mvts_viti_result[$type_drm])){
              $sorted_mvts_viti_result[$type_drm][$mvt->doc_id] =   array();
            }
            $sorted_mvts_viti_result[$type_drm][$mvt->doc_id][] = $mvt;
          }
        }
      }
      return $sorted_mvts_viti_result;
    }

}
