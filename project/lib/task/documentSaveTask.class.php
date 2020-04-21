<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class documentSaveTask
 * @author mathurin
 */
class documentSaveTask extends sfBaseTask
{
  const CSV_COURTIER_ID = 0;
  const CSV_COURTIER_NUM = 1;

  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
        new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, "id_doc"),
    ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'document';
    $this->name             = 'save';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [documentSave|INFO] task does things.
Call it with:

  [php symfony document:save|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $doc = acCouchdbManager::getClient()->find($arguments['doc_id']);
    $doc->save();
  }
}
