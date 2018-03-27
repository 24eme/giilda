<?php

class acCouchdbDocumentRenameKeyTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'ID du doc'),
       new sfCommandArgument('key', sfCommandArgument::REQUIRED, 'Clé à renommer'),
       new sfCommandArgument('new_key', sfCommandArgument::OPTIONAL, 'Nouveau nom de la clé'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'document';
    $this->name             = 'rename-key';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $doc = acCouchdbManager::getClient()->find($arguments['doc_id'], acCouchdbClient::HYDRATE_JSON);

    if(!$doc) {

        return;
    }

    if(!isset($doc->{$arguments['key']})) {

        return;
    }

    if(isset($doc->{$arguments['new_key']})) {

        return;
    }

    $value = $doc->{$arguments['key']};

    $doc->{$arguments['new_key']} = $value;

    unset($doc->{$arguments['key']});

    acCouchdbManager::getClient()->storeDoc($doc);

    $oldRev = $doc->_rev;
    $doc = acCouchdbManager::getClient()->find($arguments['doc_id'], acCouchdbClient::HYDRATE_JSON);

    echo "Le document ".$doc->_id."@".$oldRev." a été sauvé @".$doc->_rev." : la clé \"".$arguments['key']."\" a été renommé en \"". $arguments['new_key']."\"\n";
  }
}
