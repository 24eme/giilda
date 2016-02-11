<?php

class maintenanceFactureStockageNumeroInterloireTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('id', sfCommandArgument::REQUIRED, 'Id de la facture'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'facture-stockage-numero-interloire';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $facture = FactureClient::getInstance()->find($arguments['id'], acCouchdbClient::HYDRATE_JSON);

    if(isset($facture->numero_piece_comptable)) {
      return;
    }

    $facture->numero_piece_comptable = preg_replace('/^\d{2}(\d{2}).*/', '$1', $facture->date_emission) . '/' . EtablissementClient::getPrefixForRegion($facture->region) . '-' . $facture->numero_archive;

    FactureClient::getInstance()->storeDoc($facture);

    echo $facture->_id ."\n";
  }
}
