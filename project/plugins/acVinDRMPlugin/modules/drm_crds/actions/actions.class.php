<?php

class drm_crdsActions extends drmGeneriqueActions {

    public function executeCrd(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $this->drm = $this->getRoute()->getDRM();
        $this->drm->crdsInitDefault();
        $this->crdsForms = new DRMCrdsForm($this->drm);
        $this->initDeleteForm();
        if($request->getParameter('add_crd')) {
            $this->addCrdRegime = $request->getParameter('add_crd');
            $this->addCrdForm = new DRMAddCrdTypeForm($this->drm);
        }

        if ($request->isMethod(sfRequest::POST)) {
            $this->crdsForms->bind($request->getParameter($this->crdsForms->getName()));
            if ($this->crdsForms->isValid()) {
                $this->crdsForms->save();

                if($this->addCrdRegime) { 
                    $this->redirect('drm_crd', array('sf_subject' => $this->crdsForms->getObject(), 'add_crd' => $this->addCrdRegime));
                }

                $this->redirect('drm_redirect_etape', $this->crdsForms->getObject());
            }
        }
    }

    public function executeAjoutTypeCrd(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $this->drm = $this->getRoute()->getDRM();
        $this->form = new DRMAddCrdTypeForm($this->drm);
        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('drm_crd', $this->form->getObject());
            }
        }
        $this->redirect('drm_crd', $this->form->getObject());
    }

    public function executeChoixRegimeCrd(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $drm = $this->getRoute()->getDRM();
        $etablissement = $drm->getEtablissement();
        if (!$this->isTeledeclarationDrm()) {
            $this->redirect403IfIsNotTeledeclaration();
        }

        $this->form = new DRMCrdRegimeChoiceForm($drm);
        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('drm_choix_produit', $this->form->getObject());
            }
        }
    }

}
