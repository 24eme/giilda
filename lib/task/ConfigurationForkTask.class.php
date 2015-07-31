<?php

class ConfigurationForkTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('fork_doc_id', sfCommandArgument::REQUIRED, 'Fork doc id'),
   ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'configuration';
    $this->name             = 'fork';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ConfigurationForkTask|INFO] task does things.
Call it with:

  [php symfony ConfigurationForkTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $fork = ConfigurationClient::getInstance()->fork($arguments['fork_doc_id']);
    $fork->save();
  }
}
