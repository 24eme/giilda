<?php

class PaiementsSetexportedTask extends sfBaseTask
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

      // add your own options here
    ));

    $this->namespace        = 'paiements';
    $this->name             = 'setexported';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [generatePDF|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $id = $arguments['factureid'];
    $facture = FactureClient::getInstance()->find($id);
    if (!$facture) {
        throw new sfException("$id non trouvée");
    }
    if (!$facture->exist('paiements')) {
        return;
    }
    foreach($facture->paiements as $p) {
        $p->versement_comptable = 1;
    }
    $facture->versement_comptable_paiement = 1;
    $facture->save();
  }
}
