<?php

class createLiaisonEtablissementTask extends sfBaseTask
{
  protected function configure()
  {

    // // add your own arguments here
    $this->addArguments(array(
        new sfCommandArgument('fromDate', sfCommandArgument::REQUIRED, 'date au format yyyy-mm-dd à partir du quel on parcours les contrats pour créer les liens s\'il y en a')
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

    $fromDate = $arguments['fromDate']; //format YYYY-MM-DD

    $contratsInterne = VracClient::getInstance()->retrieveAllInterne($fromDate);

    foreach($contratsInterne as $contrat){

        $etabAcheteur = EtablissementClient::getInstance()->findByIdentifiant($contrat->getAcheteurObject()->getIdentifiant());
        $etabVendeur = EtablissementClient::getInstance()->findByIdentifiant($contrat->getVendeurObject()->getIdentifiant());

        if(!$etabAcheteur->haveLiaison($etabVendeur)){
            $etabAcheteur->addLiaison('ADHERENT', $etabVendeur, true);
        }

        if(!$etabVendeur->haveLiaison($etabAcheteur)){
            $etabVendeur->addLiaison('ADHERENT', $etabAcheteur, true);
        }
        $etabAcheteur->save();
        $etabVendeur->save();
    }
  }
}
