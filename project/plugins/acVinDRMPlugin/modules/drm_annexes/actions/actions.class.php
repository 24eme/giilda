<?php

class drm_annexesActions extends drmGeneriqueActions {

    public function executeAnnexes(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $this->drm = $this->getRoute()->getDRM();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->initDeleteForm();
        $this->drm->initReleveNonApurement();
        $this->annexesForm = new DRMAnnexesForm($this->drm);
        if ($request->isMethod(sfRequest::POST)) {
            $this->annexesForm->bind($request->getParameter($this->annexesForm->getName()));
            if ($this->annexesForm->isValid()) {
                $this->annexesForm->save();
                if ($request->getParameter('brouillon')) {
                   return $this->redirect('drm_etablissement', array('identifiant' => $this->drm->identifiant));
                }
                $this->redirect('drm_validation', $this->annexesForm->getObject());
            }
        }
    }

}
