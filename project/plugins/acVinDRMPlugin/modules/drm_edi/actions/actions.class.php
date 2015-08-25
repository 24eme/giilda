<?php
class drm_ediActions extends drmGeneriqueActions {

    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeVerificationEdi(sfWebRequest $request) {

        $this->md5 = $request->getParameter('md5');
        $this->csvFile = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');

        $drm = new DRM();
        $drm->identifiant = $this->identifiant;
        $drm->periode = $this->periode;
        $drm->teledeclare = true;

        $this->drmCsvEdi = new DRMImportCsvEdi($drm);
        $this->drmCsvEdi->checkCSV($this->csvFile);
    }

    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeCreationEdi(sfWebRequest $request) {

        $this->md5 = $request->getParameter('md5');
        $this->csvFile = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');

        $this->drm = new DRM();
        $this->drm->identifiant = $this->identifiant;
        $this->drm->periode = $this->periode;
        $this->drm->teledeclare = true;

        $this->drmCsvEdi = new DRMImportCsvEdi($this->drm);
        $this->drmCsvEdi->importCSV($this->csvFile);
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
