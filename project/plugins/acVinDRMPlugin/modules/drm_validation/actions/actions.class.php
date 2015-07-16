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
        $this->mouvements = $this->drm->getMouvementsCalculeByIdentifiant($this->drm->identifiant);
        if ($this->isTeledeclarationMode) {
            $this->validationCoordonneesSocieteForm = new DRMValidationCoordonneesSocieteForm($this->drm);
            $this->validationCoordonneesEtablissementForm = new DRMValidationCoordonneesEtablissementForm($this->drm);
        $this->drm->cleanAnnexes();
            
        } else {
            $this->formCampagne = new DRMEtablissementCampagneForm($this->drm->identifiant, $this->drm->campagne);
        }
        $this->no_link = false;
        if ($this->getUser()->hasOnlyCredentialDRM()) {
            $this->no_link = true;
        }

        $this->validation = new DRMValidation($this->drm, $this->isTeledeclarationMode);
        $this->produits = array();
        foreach ($this->drm->getProduits() as $produit) {
            $d = new stdClass();
            $d->version = $this->drm->version;
            $d->periode = $this->drm->periode;
            $d->produit_hash = $produit->getHash();
            $d->produit_libelle = $produit->getLibelle();
            $d->total_debut_mois = $produit->total_debut_mois;
            $d->total_entrees = $produit->total_entrees;
            $d->total_sorties = $produit->total_sorties;
            $d->total = $produit->total;
            $d->total_facturable = $produit->total_facturable;
            $this->produits[] = $d;
        }


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

    public function executeUpdateEtablissement(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->initSocieteAndEtablissementPrincipal();
        $this->form = new DRMValidationCoordonneesEtablissementForm($this->drm);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $diff = $this->form->getDiff();
                $mailManager = new DRMEmailManager($this->getMailer());
                $mailManager->setDRM($this->drm);
                $mailManager->sendMailCoordonneesOperateurChanged(CompteClient::TYPE_COMPTE_ETABLISSEMENT, $diff);
                $this->redirect('drm_validation', $this->drm);
            } else {
                
            }
        }
        //$this->redirect('drm_validation', $this->drm);
    }

    public function executeUpdateSociete(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->initSocieteAndEtablissementPrincipal();
        $this->form = new DRMValidationCoordonneesSocieteForm($this->drm);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $diff = $this->form->getDiff();
                $mailManager = new DRMEmailManager($this->getMailer());
                $mailManager->setDRM($this->drm);
                $mailManager->sendMailCoordonneesOperateurChanged(CompteClient::TYPE_COMPTE_SOCIETE, $diff);
                $this->redirect('drm_validation', $this->drm);
            } else {
                new sfException("form non valide");
            }
        }
        $this->redirect('drm_validation', $this->drm);
    }

}
