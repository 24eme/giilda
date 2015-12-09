<?php

class DRMEDIImportTask extends sfBaseTask
{

  protected function configure()
  {
      $this->addArguments(array(
         new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
      ));

      $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        new sfCommandOption('date-validation', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', false),
      ));

      $this->namespace        = 'drm';
      $this->name             = 'edi-import';
      $this->briefDescription = '';
      $this->detailedDescription = <<<EOF
  The [importVrac|INFO] task does things.
  Call it with:

    [php symfony import:drm|INFO]
EOF;

  }

    protected function execute($arguments = array(), $options = array())
    {
      // initialize the database connection
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      
      $drm = new DRM();
      $drm->identifiant = "00099001";
      $drm->periode = "200909";

      $csvFile = new CsvFile($arguments['file']);
        
      $drmCsvEdi = new DRMCsvEdi($drm);
      $drmCsvEdi->checkCSV($csvFile);

      if(!$drmCsvEdi->statut == "VALIDE") {
          foreach($drmCsvEdi->erreurs as $erreur) {
            echo sprintf("%s : %s;#%s\n", $erreur->raison, $erreur->erreur_csv, $erreur->ligne);
          }
          
          return;
      }

      $drmCsvEdi->importCSV($csvFile);

      $drm->numero_archive = "00000";
      $drm->validate();

      if($options['date-validation']) {
          $drm->valide->date_saisie = $options['date-validation'];
          $drm->valide->date_signee = $options['date-validation'];
      }

      $drm->save();

      
    }

}
