<?php

class drm_administrationActions extends drmGeneriqueActions {

    public function executeAdministration(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $this->drm = $this->getRoute()->getDRM();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->drm->initReleveNonApurement();
        $this->administrationForm = new DRMAdministrationForm($this->drm);
        if ($request->isMethod(sfRequest::POST)) {
            $this->administrationForm->bind($request->getParameter($this->administrationForm->getName()));
            if ($this->administrationForm->isValid()) {
                $this->administrationForm->save();
                $this->redirect('drm_validation', $this->administrationForm->getObject());
            }
        }
    }

}
