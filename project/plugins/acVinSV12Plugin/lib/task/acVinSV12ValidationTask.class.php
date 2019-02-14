<?php

class acVinSV12ValidationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'ID Document'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('devalide', null, sfCommandOption::PARAMETER_REQUIRED, 'Devalidation de la SV12 si déjà validée', false),
      // add your own options here
    ));

    $this->namespace        = 'sv12';
    $this->name             = 'validation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $sv12 = acCouchdbManager::getClient('SV12')->find($arguments['doc_id']);

    if ($options['devalide'] && $sv12->isValidee()) {
      $sv12->devalide();
      $sv12->updateTotaux();
      $sv12->validate();
      $sv12->save();
    }

    $sv12->updateVracs();

  }
}
