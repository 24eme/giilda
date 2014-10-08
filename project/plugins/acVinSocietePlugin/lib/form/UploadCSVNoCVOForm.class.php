<?php

class UploadCSVNoCVOForm extends sfForm {
    public function configure() {
      $this->setWidget('file', new sfWidgetFormInputFile(array('label' => 'Fichier')));
      $this->setValidator('file', new ValidatorImportCsv(array('file_path' => sfConfig::get('sf_data_dir').'/upload')));
      
      $this->widgetSchema->setLabel('file', "Choisir un fichier :");
      $this->widgetSchema->setNameFormat('csv_noCvo[%s]');
    }
}