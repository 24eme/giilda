<?php

class maintenanceFactureCodeComptableTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('id', sfCommandArgument::REQUIRED, 'Id de la facture'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'factureCodeComptable';
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

    $changed = false;
    foreach($facture->lignes as $group) {
        foreach($group as $ligne) {
            $codeComptableOrigine = $ligne->_get('produit_identifiant_analytique');
            if($codeComptableOrigine != $ligne->getProduitIdentifiantAnalytique()) {
                echo $facture->_id .";".$codeComptableOrigine.";".$ligne->getProduitIdentifiantAnalytique()."\n";
                $changed = true;
            }
        }
    }

    if($changed) {
        $facture->save();
    }
  }
}
