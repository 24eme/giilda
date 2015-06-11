<?php

class drm_administrationActions extends drmGeneriqueActions {

    public function executeAdministration(sfWebRequest $request) {
        $this->initSocieteAndEtablissementPrincipal();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->drm = $this->getRoute()->getDRM();
        $this->request = $request;
    }
    
}
