<?php

class GetInformationsContractuelsTask extends sfBaseTask
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
    $this->name             = 'get-info';
    $this->briefDescription = 'Retourne les emails des différents signataires';
    $this->detailedDescription = <<<EOF
The [vrac:get-info doc_id|INFO] retourne les emails des signataires
Call it with:

  [php symfony vrac:get-info doc_id |INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $doc_id = $arguments['doc_id'];

    $doc = acCouchdbManager::getClient()->find($doc_id);
    $vendeur = CompteClient::getInstance()->findByIdentifiant($doc->vendeur_identifiant);
    $acheteur = CompteClient::getInstance()->findByIdentifiant($doc->acheteur_identifiant);
    $courtier = ($doc->mandataire_exist)
                ? CompteClient::getInstance()->findByIdentifiant($doc->mandataire_identifiant)
                : false;

    echo "$doc->_id :".PHP_EOL;
    echo "Acheteur : $acheteur->email".PHP_EOL;
    echo "Vendeur : $vendeur->email".PHP_EOL;
    echo ($courtier)
        ? "Courtier : $courtier->email".PHP_EOL
        : "";
    echo PHP_EOL;
  }
}
