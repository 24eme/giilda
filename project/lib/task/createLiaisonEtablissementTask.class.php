<?php

class createLiaisonEtablissementTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('idEtablissement1', sfCommandArgument::REQUIRED, 'Id de l\'établissement 1 pour liaison'),
      new sfCommandArgument('idEtablissement2', sfCommandArgument::REQUIRED, 'Id de l\'établissement 2 pour liaison'),

    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'create';
    $this->name             = 'liaison';
    $this->briefDescription = 'Créer les liaisons entre deux établissements';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {

    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);

    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $idEtablissement1 = $arguments['idEtablissement1'];
    $idEtablissement2 = $arguments['idEtablissement2'];

    $etab1 = EtablissementClient::getInstance()->find($idEtablissement1);
    $etab2 = EtablissementClient::getInstance()->find($idEtablissement2);

    $etab1->addLiaison('ADHERENT', $etab2, true);
    $etab2->addLiaison('ADHERENT', $etab1, true);

    $etab1->save();
    $etab2->save();


  }
}
