<?php

class DRMEDIImportTask extends sfBaseTask
{

  protected function configure()
  {
      $this->addArguments(array(
         new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
         new sfCommandArgument('periode', sfCommandArgument::REQUIRED, "Periode de la DRM"),
         new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, "Identifiant de l'établissement"),
         new sfCommandArgument('numero_archive', sfCommandArgument::OPTIONAL, "Numéro d'archive"),
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
      if($arguments['numero_archive']) {
        $drm->numero_archive = $arguments['numero_archive'];
      }

      if(DRMClient::getInstance()->find('DRM-'.$drm->identifiant.'-'.$drm->periode, acCouchdbClient::HYDRATE_JSON)) {
        echo "Existe;".'DRM-'.$drm->identifiant.'-'.$drm->periode."\n";
        return;
      }

      if(!EtablissementClient::getInstance()->find($drm->identifiant, acCouchdbClient::HYDRATE_JSON)) {
          echo "L'établissement n'existe pas;".$drm->identifiant."\n";
          return;
      } 

      $drmCsvEdi = new DRMImportCsvEdiStandalone($arguments['file'], $drm);
      $drmCsvEdi->checkCSV();


      if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
          $csv = $drmCsvEdi->getCsv();
          foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
            echo sprintf("%s : %s;#%s\n", $erreur->diagnostic, $erreur->csv_erreur, implode(";", $csv[$erreur->num_ligne-1]));
          }
          return;
      }

      
      try {
        $drmCsvEdi->importCSV();
        $drm->update();
        $drm->validate();

        if($options['date-validation']) {
            $drm->valide->date_saisie = $options['date-validation'];
            $drm->valide->date_signee = $options['date-validation'];
        }

        $drm->save();
      
      } catch(Exception $e) {
        echo $e->getMessage().";#".$arguments['periode'].";".$arguments['identifiant']."\n";
        return;
      }
      
      echo "Création ; ".$drm->_id."\n";
      
    }

}
