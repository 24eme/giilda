<?php

class GenerationGenerateTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
    ));

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
			    new sfCommandOption('generation', null, sfCommandOption::PARAMETER_REQUIRED, 'The generation id'),
			    new sfCommandOption('page2perpage', null, sfCommandOption::PARAMETER_REQUIRED, 'The generation id', true),
			    new sfCommandOption('debug', null, sfCommandOption::PARAMETER_REQUIRED, 'Debug', false),
      // add your own options here
    ));

    $this->namespace        = 'generation';
    $this->name             = 'generate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [GenerationGenerate|INFO] task does things.
Call it with:

  [php symfony GenerationGenerate|INFO]
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
        	$g = GenerationClient::getInstance()->getGenerator($generation, $this->configuration, $options);
        	echo $g->generate()."\n";
      } catch(Exception $e) {
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
