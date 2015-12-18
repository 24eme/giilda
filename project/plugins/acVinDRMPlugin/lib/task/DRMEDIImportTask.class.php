<?php

class DRMEDIImportTask extends sfBaseTask
{

  protected function configure()
  {
      $this->addArguments(array(
         new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
         new sfCommandArgument('periode', sfCommandArgument::REQUIRED, "Periode de la DRM"),
         new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, "Identifiant de l'établissement"),
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
      $drm->identifiant = $arguments['identifiant'];
      $drm->periode = $arguments['periode'];

      if(DRMClient::getInstance()->find('DRM-'.$drm->identifiant.'-'.$drm->periode, acCouchdbClient::HYDRATE_JSON)) {
        echo "Existe;".'DRM-'.$drm->identifiant.'-'.$drm->periode."\n";
        return;
      }

      $csvFile = new CsvFile($arguments['file']);
        
      $drmCsvEdi = new DRMCsvEdi($drm);
      $drmCsvEdi->checkCSV($csvFile);

      if($drmCsvEdi->statut != "VALIDE") {
          foreach($drmCsvEdi->erreurs as $erreur) {
            echo sprintf("%s : %s;#%s\n", $erreur->raison, $erreur->erreur_csv, $erreur->ligne);
          }
          return;
      }

      try {
        $drmCsvEdi->importCSV($csvFile);
        $drm->update();
        $drm->numero_archive = "00000";
        $drm->validate();

        if($options['date-validation']) {
            $drm->valide->date_saisie = $options['date-validation'];
            $drm->valide->date_signee = $options['date-validation'];
        }

        if(!EtablissementClient::getInstance()->find($drm->identifiant, acCouchdbClient::HYDRATE_JSON)) {
          throw new Exception("L'établissement n'extiste pas");
        }
        
        $drm->save();
      
      } catch(Exception $e) {
        echo $e->getMessage().";#".$arguments['periode'].";".$arguments['identifiant']."\n";
        return;
      }
      
      echo "Création ; ".$drm->_id."\n";
      
    }

}
