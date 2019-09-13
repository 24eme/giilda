<?php

class drm_ediActions extends drmGeneriqueActions {

    public function executeCsv(sfWebRequest $request) {
        $identifiant = $request->getParameter('identifiant');
        $periode = $request->getParameter('periode');

        $csv = CSVClient::getInstance()->findFromIdentifiantPeriode($identifiant, $periode);

        $filename = 'import_edi_'.$identifiant.'_'.$periode.'.csv';

        $this->response->setContent(file_get_contents($csv->getAttachmentUri($filename)));
        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', "attachment; filename=" . $filename);

        return sfView::NONE;
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeVerificationEdi(sfWebRequest $request) {

        set_time_limit(300);

        $this->md5 = $request->getParameter('md5');
        $csvFilePath = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');

        $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);

        if(!$drm) {
            $drm = new DRM();
            $drm->identifiant = $this->identifiant;
            $drm->periode = $this->periode;
            $drm->teledeclare = true;
        }

        if (!$request->getParameter('nocheck')) {
            $drmCsvEdi = new DRMImportCsvEdi($csvFilePath, $drm);
            $drmCsvEdi->checkCSV();
            $this->csvDoc = $drmCsvEdi->getCsvDoc();
        }else{
            $this->csvDoc = CsvClient::getInstance()->findFromIdentifiantPeriode($this->identifiant, $this->periode);
        }

        if(!$drm->isNew()) {
            $this->drm = $drm;
            return sfView::SUCCESS;
        }

        if($this->csvDoc->statut == DRMCsvEdi::STATUT_VALIDE) {
          return $this->redirect('drm_creation_fichier_edi', array('periode' => $this->periode, 'md5' => $this->md5, 'identifiant' => $this->identifiant));
        }

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

        set_time_limit(400);

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
