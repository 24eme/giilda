<?php

class generatePDFTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
    ));

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
			    new sfCommandOption('generation', null, sfCommandOption::PARAMETER_REQUIRED, 'The generation id'),
			    new sfCommandOption('page2perpage', null, sfCommandOption::PARAMETER_REQUIRED, 'The generation id', true),
      // add your own options here
    ));

    $this->namespace        = 'generate';
    $this->name             = 'PDF';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [generatePDF|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $generation = GenerationClient::getInstance()->find($options['generation']);
    if (!$generation) {
      echo $options['generation']." n'est pas un document valide\n";
      exit(1);
    }
    $g = new GenerationPDF($generation, $this->configuration);
    echo $g->generatePDF();
  }
}
