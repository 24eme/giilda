<?php

class drm_ajout_produitActions extends drmGeneriqueActions {

    public function executeChoixPoduits(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $this->drm = $this->getRoute()->getDRM();
        $this->certificationsProduits = $this->drm->declaration->getProduitsDetailsByCertifications(true);
        $this->form = new DRMProduitsChoiceForm($this->drm);

        /*$this->formAddProduitsByCertifications = array();
        
        foreach ($this->certificationsProduits as $certificationProduits) {
            $this->formAddProduitsByCertifications[$certificationProduits->certification->getHashForKey()] = new DRMAddProduitByCertificationForm($this->drm, array('configurationCertification' => $certificationProduits->certification));
        }*/

        $this->hasRegimeCrd = $this->drm->getEtablissement()->hasRegimeCrd();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        if ($this->hasRegimeCrd && $request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                if($request->hasParameter('add_produit')) {
                    $this->redirect($this->redirect('drm_choix_produit', array('sf_subject' => $this->drm, 'add_produit' => $request->getParameter('add_produit'))));
                }
                
                return $this->redirect('drm_edition', $this->form->getObject());
            }
        }

        if($this->hasRegimeCrd && $request->hasParameter('add_produit')) {
            $this->formAddProduitsByCertification = new DRMAddProduitByCertificationForm($this->drm, array('configurationCertification' => $request->getParameter('add_produit')));
        }

        if (!$this->hasRegimeCrd){
           $this->crdRegimeForm = new DRMCrdRegimeChoiceForm($this->drm);
        }
    }

    public function executeChoixAjoutPoduits(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $cerfificationParam = $request['certification_hash'];
        if (!$cerfificationParam || !preg_match('/^\-declaration\-certifications\-([a-zA-Z]*)/', $cerfificationParam)) {
            throw new sfException("le format de la certification n'est pas correct : $cerfificationParam");
        }
        $cerfificationHash = str_replace('-', '/', $cerfificationParam);
        $this->drm = $this->getRoute()->getDRM();
        $certificationDrm = $this->drm->getOrAdd($cerfificationHash);
        if (!$certificationDrm) {
            throw new sfException("La certification n'existe pas dans la DRM : $cerfificationHash");
        }
        $this->form = new DRMAddProduitByCertificationForm($this->drm, array('configurationCertification' => $certificationDrm->getHash()));
        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('drm_choix_produit', $this->form->getDrm());
            }
        }
    }

}
