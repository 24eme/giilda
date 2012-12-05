<?php

class stocksComponents extends sfComponents {

    public function executeChooseEtablissement() {
        if (!$this->form) {
          $this->form = new StocksEtablissementChoiceForm('INTERPRO-inter-loire',array('identifiant' => $this->identifiant));
        }
    }

    public function executeMouvements() {
        $this->mouvements_drm = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne);
        $this->mouvements_sv12 = SV12MouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne);
    }

}
