<?php

class UploadCSVRevendicationForm extends CreateRevendicationForm {

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
      parent::configure();     
      $this->setWidget('file', new sfWidgetFormInputFile(array('label' => 'Fichier')));
      $this->setValidator('file', new ValidatorImportCsv(array('file_path' => sfConfig::get('sf_data_dir').'/upload')));
      
      $this->widgetSchema->setLabel('file', "Choisir un fichier :");
      $this->widgetSchema->setNameFormat('csv[%s]');
      $this->widgetSchema->setNameFormat('csvRevendication[%s]');
    }
}
