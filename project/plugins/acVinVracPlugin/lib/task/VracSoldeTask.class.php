<?php

class VracSoldeTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'Document ID'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'vrac';
    $this->name             = 'solde';
    $this->briefDescription = 'Retourne les emails des différents signataires';
    $this->detailedDescription = <<<EOF
The [vrac:solde doc_id|INFO] retourne les emails des signataires
Call it with:

  [php symfony vrac:solde doc_id |INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $doc_id = $arguments['doc_id'];

    $doc = acCouchdbManager::getClient()->find($doc_id);
    $doc->solder();
    $doc->save();

  }
}
