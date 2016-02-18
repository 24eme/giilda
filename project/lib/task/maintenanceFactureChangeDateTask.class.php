<?php

class maintenanceFactureChangeDateTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('id', sfCommandArgument::REQUIRED, 'Id de la facture'),
       new sfCommandArgument('date', sfCommandArgument::REQUIRED, 'Nouvelle date de la facture'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'factureChangeDate';
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
    
    $facture = FactureClient::getInstance()->find($arguments['id']);
    $facture->campagne = null;
    $facture->numero_archive = null;
    $facture->numero_interloire = null;
    $facture->versement_comptable = 0;
    $facture->date_facturation = $arguments['date'];
    $facture->storeDatesCampagne($arguments['date']);
    $facture->save();

    echo $facture->_id ."\n";
  }
}
