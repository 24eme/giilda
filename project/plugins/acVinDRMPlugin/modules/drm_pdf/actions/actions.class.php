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
class drm_pdfActions extends drmGeneriqueActions {

    public function executeLatex(sfWebRequest $request) {

        if ($this->isTeledeclarationDrm()) {
            $this->initSocieteAndEtablissementPrincipal();
        }

        $this->setLayout(false);
        $this->drm = $this->getRoute()->getDrm();
        $this->forward404Unless($this->drm);

        if (!$this->drm->isValidee()) {
            $this->drm->cleanDeclaration();
            $this->drm->validate(array('isTeledeclarationMode' => $this->isTeledeclarationDrm(), 'validation_step' => true, 'no_vracs' => true));
        }


        $latex = new DRMLatex($this->drm);
        // $latex->getLatexFileContents();
        $latex->echoWithHTTPHeader($request->getParameter('type'));
        exit;
    }

}
