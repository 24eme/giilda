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
			    new sfCommandOption('debug', null, sfCommandOption::PARAMETER_REQUIRED, 'Debug', false),
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
	$generationids = GenerationClient::getInstance()->getGenerationIdEnAttente();
    }

    foreach ($generationids as $gid) { 
      echo "Generation de $gid\n";
      try {
	$generation = GenerationClient::getInstance()->find($gid);
	if (!$generation) {
	  throw new sfException("$gid n'est pas un document valide");
	}
	$g = null;
	switch ($generation->type_document) {
	case GenerationClient::TYPE_DOCUMENT_FACTURES:
	  $g = new GenerationFacturePDF($generation, $this->configuration, $options);
	  break;
	  
	case GenerationClient::TYPE_DOCUMENT_DS:
	  $g = new GenerationDSPDF($generation, $this->configuration, $options);
	  break;
      
        case GenerationClient::TYPE_DOCUMENT_RELANCE:
	  $g = new GenerationRelancePDF($generation, $this->configuration, $options);
	  break;

	default:
	  throw new sfException($generation->type_document." n'est pas un type supporté");
	}
	echo $g->generatePDF()."\n";
      }catch(Exception $e) {
	if ($options['debug']) {
	  throw $e;
	}
	$generation->statut = GenerationClient::GENERATION_STATUT_ENERREUR;
	$generation->message = $e->getMessage();
	$generation->save();
      }
    }
  }
}
