<?php

class DRMStoreXMLRetourTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('url', sfCommandArgument::REQUIRED, "Url de récupération"),
        ));

        $this->addOptions(array(
            new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'Verbose', false),
            new sfCommandOption('force-update', null, sfCommandOption::PARAMETER_REQUIRED, 'Force Update', false),
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'storeXMLRetour';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
    The task does things.
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

      try {
        $drm = DRMClient::storeXMLRetourFromURL($arguments['url'], $options['verbose'], $options['force-update']);
        if ($drm) { //Si pas $drm c'est qu'il y avait déjà le même XML
          echo $drm->_id." mis à jour avec la DRM de retour attachée\n";
          return 0;
        }else{
          return 1;
        }
      }catch(sfException $e) {
        echo "Erreur ".$arguments['url']. " : " .$e->getMessage()."\n";
        return 200;
      }

    }

}
