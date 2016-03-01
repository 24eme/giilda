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
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_REQUIRED, 'Date', null),
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


    $date = $options['date'];

    if(!$date) {
        $date = preg_replace("/^CONFIGURATION-([0-9]{4})([0-9]{2})([0-9]{2})$/", '\1-\2-\3', $arguments['fork_doc_id']); 
    }

    if(!$date) {
        echo "/!\ Aucune date d'utilisation n'a été spécifiée\n";
        return;
    }

    if(!preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date)) {
      echo sprintf("/!\ La date d'utilisation n'est pas valide : %s. Format accepté (YYYY-mm-dd)\n", $options['date']);
      return;
    }
    
    ConfigurationClient::getInstance()->cacheResetConfiguration();

    $configuration = ConfigurationClient::getConfiguration($date);

    if(!$configuration) {

        echo sprintf("Aucune configuration trouvée\n");
        return;
    }

    $fork = ConfigurationClient::getInstance()->fork($arguments['fork_doc_id'], $configuration);
    $fork->save();

    echo sprintf("Fork %s créé à partir de la configuration %s\n", $fork->_id, $configuration->_id);

    $current = CurrentClient::getCurrent();
    $current->configurations->add($date, $fork->_id);
    $current->reorderConfigurations();
    $current->save();

    echo sprintf("La nouvelle configuration %s est configurée pour être utilisée à partir de %s\n", $fork->_id, $date);
  }
}
