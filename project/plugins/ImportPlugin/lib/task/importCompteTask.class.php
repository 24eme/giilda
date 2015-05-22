<?php

class importCompteTask extends importAbstractTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'compte';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importEtablissement|INFO] task does things.
Call it with:

  [php symfony importCompte|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $context = sfContext::createInstance($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csv = new CompteCsvFile($arguments['file']);
    $csv->importComptes();

    // add your code here
  }
}
