<?php

class FactureAddRelanceTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
			    new sfCommandArgument('factureid', null, sfCommandOption::PARAMETER_REQUIRED, 'Facture id'),
    ));

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                new sfCommandOption('date', null, sfCommandOption::PARAMETER_REQUIRED, 'Date relance', date('Y-m-d')),
      // add your own options here
    ));

    $this->namespace        = 'facture';
    $this->name             = 'addrelance';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [addrelance|INFO] task does things.
Call it with:

  [php symfony facture::addrelance|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $facture = FactureClient::getInstance()->find($arguments['factureid']);
    if ($facture) {
        $facture->addRelance($options['date']);
        $facture->save();
    }
  }
}
