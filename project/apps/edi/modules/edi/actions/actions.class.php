<?php

class ediActions extends sfActions {

    public function executeDrmCreationEdi(sfWebRequest $request) {

      $this->identifiant = $request->getParameter('identifiant');
      $this->periode = $request->getParameter('periode');

      $this->creationEdiDrmForm = new DRMChoixCreationForm(array(), array('identifiant' => $this->identifiant, 'periode' => $this->periode, 'only-edi' => true));
      $drm = DRMClient::getInstance()->findOrCreateFromEdiByIdentifiantAndPeriode($this->identifiant,$this->periode);
      if ($request->isMethod(sfWebRequest::POST)) {
          $this->creationEdiDrmForm->bind($request->getParameter($this->creationEdiDrmForm->getName()), $request->getFiles($this->creationEdiDrmForm->getName()));
          if ($this->creationEdiDrmForm->isValid()) {

            if(!$drm->isCreationEdi()){
              throw new sfException("La DRM n'est pas en création EDi");
            }

            $md5 = $this->creationEdiDrmForm->getValue('edi-file')->getMd5();
            $csvFilePath = sfConfig::get('sf_data_dir') . '/upload/' . $md5;

            $this->drmCsvEdi = new DRMImportCsvEdi($csvFilePath, $drm);
            $this->drmCsvEdi->checkCSV();
            $csvArrayErreurs = $this->drmCsvEdi->getCsvArrayErreurs();
            unlink($csvFilePath);

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
              $this->drmCsvEdi->importCSV(true);
            }
            
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
