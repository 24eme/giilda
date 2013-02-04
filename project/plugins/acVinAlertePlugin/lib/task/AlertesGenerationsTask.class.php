<?php

class AlertesGenerationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
          new sfCommandArgument('alertes', sfCommandArgument::IS_ARRAY, "Liste d'alertes à lancer", array())
    ));

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'generate';
    $this->name             = 'alertes';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [generateAlertes|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $container = new AlerteGenerationsContainer();

    if(count($arguments['alertes']) > 0) {
      foreach($arguments['alertes'] as $name) {
        $container->add($name);
      }
    } else {
      $container->addAll();
    }

    $container->creations();
    $container->updates();
  }
}
