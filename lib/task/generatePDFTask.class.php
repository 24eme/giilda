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

    $generationids = array();
    if ($options['generation']) {
	$generationids[] = $options['generation'];
    }else{
	$generationids = GenerationClient::getInstance()->getGenerationIdEnCours();
    }

//    print count($generationids)." generation(s) à réaliser\n";
   
    foreach ($generationids as $gid) { 
      $generation = GenerationClient::getInstance()->find($gid);
      if (!$generation) {
        echo $options['generation']." n'est pas un document valide\n";
	continue;
      }
      $g = null;
      switch ($generation->type_document) {
          case GenerationClient::TYPE_DOCUMENT_FACTURES:
              $g = new GenerationFacturePDF($generation, $this->configuration, $options);
              break;

          case GenerationClient::TYPE_DOCUMENT_DS:
              $g = new GenerationDSPDF($generation, $this->configuration, $options);
              break;
      }
      $g->preGeneratePDF();
      echo $g->generatePDF()."\n";
    }
  }
}
