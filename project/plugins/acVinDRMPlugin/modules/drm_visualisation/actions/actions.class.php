<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of actions
 *
 * @author mathurin
 */
class drm_visualisationActions extends drmGeneriqueActions {

    public function executeVisualisation(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->no_link = false;
        if ($this->getUser()->hasOnlyCredentialDRM()) {
            $this->no_link = true;
        }
        $this->hide_rectificative = $request->getParameter('hide_rectificative');
        $this->drm_suivante = $this->drm->getSuivante();     
        $this->mouvements = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndPeriode($this->drm->identifiant, $this->drm->periode);
        $this->createMouvementsByProduits($this->mouvements);
        $this->recapCvo = $this->recapCvo();
    }

    public function recapCvo() {
        $recapCvo = new stdClass();
        $recapCvo->totalVolumeDroitsCvo = 0;
        $recapCvo->totalVolumeReintegration = 0;
        $recapCvo->totalPrixDroitCvo = 0;
        foreach ($this->mouvements as $mouvement) {
            if ($mouvement->facturable) {
                $recapCvo->totalPrixDroitCvo += $mouvement->volume * -1 * $mouvement->cvo;
                $recapCvo->totalVolumeDroitsCvo += $mouvement->volume * -1;
            }
            if ($mouvement->type_hash == 'entrees/reintegration') {
                $recapCvo->totalVolumeReintegration += $mouvement->volume;
            }
        }
        return $recapCvo;
    }

}
