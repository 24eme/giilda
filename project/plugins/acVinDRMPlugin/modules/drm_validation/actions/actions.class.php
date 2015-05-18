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
class drm_validationActions extends drmGeneriqueActions {

    public function executeValidation(sfWebRequest $request) {
        set_time_limit(180);
        $this->drm = $this->getRoute()->getDRM();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->initSocieteAndEtablissementPrincipal();
        $this->mouvements = $this->drm->getMouvementsCalculeByIdentifiant($this->drm->identifiant, $this->isTeledeclarationMode);

        $this->no_link = false;
        if ($this->getUser()->hasOnlyCredentialDRM()) {
            $this->no_link = true;
        }

        $this->validation = new DRMValidation($this->drm, $this->isTeledeclarationMode);

        $this->form = new DRMCommentaireForm($this->drm);

        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($request->getParameter('brouillon')) {
            $this->form->save();
            return $this->redirect('drm_etablissement', $this->drm->getEtablissement());
        }

        if (!$this->validation->isValide()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        $this->drm->validate(array('isTeledeclarationMode' => $this->isTeledeclarationMode));
        $this->drm->save();

        DRMClient::getInstance()->generateVersionCascade($this->drm);

        $this->redirect('drm_visualisation', array('identifiant' => $this->drm->identifiant,
            'periode_version' => $this->drm->getPeriodeAndVersion(),
            'hide_rectificative' => 1));
    }

}
