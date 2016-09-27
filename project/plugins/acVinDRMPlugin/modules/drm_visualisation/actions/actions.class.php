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
	try {
		$this->drm = $this->getRoute()->getDRM();
	        $this->initSocieteAndEtablissementPrincipal();
	        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
	        $this->no_link = false;
	        if ($this->getUser()->hasOnlyCredentialDRM()) {
	            $this->no_link = true;
	        }
	        $this->hide_rectificative = $request->getParameter('hide_rectificative');
	        $this->drm_suivante = $this->drm->getSuivante();
	        $this->mouvements = array();
		foreach( DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndPeriode($this->drm->identifiant, $this->drm->periode, $this->isTeledeclarationMode) as $m) {
	                if (preg_match('/'.$this->drm->identifiant.'/', $m->doc_id)) {
				$this->mouvements[] = $m;
			}
		}
	
	        $this->recapCvo = DRMClient::recapCvo($this->mouvements);
	}catch(sfException $e) {
		$this->getUser()->setFlash("notice", 'Impossible de visualiser une DRM non validée');
		return $this->redirect('drm_etablissement', array('identifiant' => $request->getParameter('identifiant')));
	}
    }

}
