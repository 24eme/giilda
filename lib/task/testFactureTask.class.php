<?php

class testFactureTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'test';
    $this->name             = 'facture';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

  [php symfony testFacture|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $facture = new Facture();
    $facture->_id = 'FACTURE-20120101-123';
    $facture->identifiant = '20120101-123';
    $facture->date_emission = '2012-01-01';
    $facture->campagne = '2011-2012';
    $facture->emetteur->adresse = "Chateau InterLoire";
    $facture->emetteur->code_postal = '44120';
    $facture->emetteur->ville = 'Balieue Nantaise';
    $facture->emetteur->service_facturation = 'Neilly';
    $facture->emetteur->telephone = '0212321232';
    $facture->client_identifiant = 'ETABLISSEMENT-123';
    $facture->client_reference = '123';
    $facture->total_ht = 100;
    $facture->total_ttc = 119.6;
    $facture->origines = array('DRM-123-2012-01' => 'DRM de janvier', 'DRM-123-2011-12' => 'DRM de décembre');
    $facture->save();
  }
}
