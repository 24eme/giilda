<?php

class drm_ediActions extends drmGeneriqueActions {

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeVerificationEdi(sfWebRequest $request) {

        $this->md5 = $request->getParameter('md5');
        $csvFilePath = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');

        $drm = new DRM();
        $drm->identifiant = $this->identifiant;
        $drm->periode = $this->periode;
        $drm->teledeclare = true;

        $this->drmCsvEdi = new DRMImportCsvEdi($csvFilePath, $drm);
        $this->drmCsvEdi->checkCSV();
        $this->creationEdiDrmForm = new DRMChoixCreationForm(array('type_creation' => DRMClient::DRM_CREATION_EDI), array('identifiant' => $this->identifiant, 'periode' => $this->periode));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->creationEdiDrmForm->bind($request->getParameter($this->creationEdiDrmForm->getName()), $request->getFiles($this->creationEdiDrmForm->getName()));
            if ($this->creationEdiDrmForm->isValid()) {
                $md5 = $this->creationEdiDrmForm->getValue('edi-file')->getMd5();
                return $this->redirect('drm_verification_fichier_edi', array('identifiant' => $this->identifiant, 'periode' => $this->periode, 'md5' => $md5));
            }
        }
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeCreationEdi(sfWebRequest $request) {

        $this->md5 = $request->getParameter('md5');
        $csvFilePath = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');


        $this->drm = DRMClient::getInstance()->findOrCreateFromEdiByIdentifiantAndPeriode($this->identifiant,$this->periode, true);

        $this->drmCsvEdi = new DRMImportCsvEdi($csvFilePath, $this->drm);
        $this->drmCsvEdi->importCSV(true);
        $this->redirect('drm_validation', $this->drm);
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeExportEdi(sfWebRequest $request) {
        $this->setLayout(false);
        $drm = $this->getRoute()->getDRM();
        $this->drmCsvEdi = new DRMExportCsvEdi($drm);

        $filename = 'export_edi_' . $drm->identifiant . '_' . $drm->periode;


        $attachement = "attachment; filename=" . $filename . ".csv";

        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', $attachement);
    }

}
