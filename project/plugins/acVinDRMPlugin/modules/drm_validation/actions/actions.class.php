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
        $this->formCampagne = new DRMEtablissementCampagneForm($this->drm->identifiant, $this->drm->campagne);
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

}
