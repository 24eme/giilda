<?php

class ediActions extends sfActions {

    public function executeDrmCreationEdi(sfWebRequest $request) {

      $this->identifiant = $request->getParameter('identifiant');
      $this->periode = $request->getParameter('periode');

      $this->creationEdiDrmForm = new DRMChoixCreationForm(array(), array('identifiant' => $this->identifiant, 'periode' => $this->periode, 'only-edi' => true));
      $drm = DRMClient::getInstance()->findOrCreateFromEdiByIdentifiantAndPeriode($this->identifiant,$this->periode, true);
      if ($request->isMethod(sfWebRequest::POST)) {
          $this->creationEdiDrmForm->bind($request->getParameter($this->creationEdiDrmForm->getName()), $request->getFiles($this->creationEdiDrmForm->getName()));
          if ($this->creationEdiDrmForm->isValid()) {

            $md5 = $this->creationEdiDrmForm->getValue('edi-file')->getMd5();
            $csvFilePath = sfConfig::get('sf_data_dir') . '/upload/' . $md5;

            $this->drmCsvEdi = new DRMImportCsvEdi($csvFilePath, $drm, true);
            $this->drmCsvEdi->checkCSV();
            $csvArrayErreurs = $this->drmCsvEdi->getCsvArrayErreurs();

            //CSV RESPONSE

            $filename = sprintf("DRM_EDI_%s_%s.csv",$this->identifiant,$this->periode);
            $handle = fopen('php://memory', 'r+');

            foreach ($csvArrayErreurs as $csvRow) {
                fputcsv($handle, $csvRow,';');
            }

            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            if(!$this->drmCsvEdi->getCsvDoc()->hasErreurs(CSVClient::LEVEL_ERROR)){
              if(($drm->etape == DRMClient::ETAPE_VALIDATION_EDI) && !$drm->isNew()){
                $drm->delete();
                $drm = DRMClient::getInstance()->findOrCreateFromEdiByIdentifiantAndPeriode($this->identifiant,$this->periode, true);
                $this->drmCsvEdi = new DRMImportCsvEdi($csvFilePath, $drm, true);
              }
              $this->drmCsvEdi->importCSV(true);
            }
            unlink($csvFilePath);

            $this->setLayout(false);
            $attachement = "attachment; filename=" . $filename ;
            $this->response->setContent($content);
            $this->response->setContentType('text/csv');
            $this->response->setHttpHeader('Content-Disposition', $attachement);
            $this->response->setContent($content);
            return sfView::NONE;
          }
      }
    }

}
