<?php

class DRMCcompareXMLsTask extends sfBaseTask
{

  protected function configure()
  {
  	$this->addArguments(array(
      new sfCommandArgument('drmid', sfCommandArgument::REQUIRED, 'Cible contenant les DRM en retour de CIEL')
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', false),
    ));

    $this->namespace        = 'drm';
    $this->name             = 'compareXMLs';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfProjectConfiguration::getActive()->loadHelpers("Partial");
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $contextInstance = sfContext::createInstance($this->configuration);

    $drm = DRMClient::getInstance()->find($arguments['drmid']);
    if ($drm->areXMLIdentical()) {
      $drm->getOrAdd('transmission_douane')->add("coherente", true);
      $drm->getOrAdd('transmission_douane')->add("diff",null);
      echo $drm->_id." : XML sont identiques\n";
    }else{
      echo $drm->_id." : XML sont differents :-(\n";
      $comp = $drm->getXMLComparison();
      $drm->getOrAdd('transmission_douane')->add("coherente",false);
      $drm->getOrAdd('transmission_douane')->add("diff", serialize($comp->getDiff()));
      if ($options['checking']) {
        var_dump($comp->getDiff());
      }
    }
     $drm->save();

  }

}
