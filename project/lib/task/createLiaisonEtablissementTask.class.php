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
        echo("contrat:".$contrat->_id."\n");
        $etabAcheteur = EtablissementClient::getInstance()->findByIdentifiant($contrat->getAcheteurObject()->getIdentifiant());
        $etabVendeur = EtablissementClient::getInstance()->findByIdentifiant($contrat->getVendeurObject()->getIdentifiant());

        if($etabAcheteur == $etabVendeur){
            echo("Acheteur et Vendeur sont les mêmes : ".$etabAcheteur->_id);
            continue;
        }

        if(!$etabAcheteur->haveLiaison($etabVendeur)){
            echo("Liaison crée entre ".$etabAcheteur->_id." et ".$etabVendeur->_id."\n");
            $etabAcheteur->addLiaison('ADHERENT', $etabVendeur, true);
            $etabAcheteur->save();
        }

        if(!$etabVendeur->haveLiaison($etabAcheteur)){
            echo("Liaison crée entre ".$etabVendeur->_id." et ".$etabAcheteur->_id."\n");
            $etabVendeur->addLiaison('ADHERENT', $etabAcheteur, true);
            $etabVendeur->save();
        }
    }
  }
}
