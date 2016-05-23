<?php

class documentUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'ID du document'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'document';
    $this->name             = 'update';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $doc_id = $arguments['doc_id'];

    $doc = acCouchdbManager::getClient()->find($doc_id);
    if(preg_match("/^DRM-/", $doc_id)) {
      $doc = acCouchdbManager::getClient()->find($doc_id);
      $doc->declaration->cleanNoeuds();
      $doc->update();
      $doc->save();
    }
    $doc->update();
    if ($doc->save()) {
	echo "$doc_id saved\n";
    }

    /*if(preg_match("/^SV12-/", $doc_id)) {
      $doc = acCouchdbManager::getClient()->find($doc_id);
      $doc->updateTotaux();
      $doc->save();
    }*/
  }
} 
