<?php

class maintenanceSV12MouvementsUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'sv12-mouvements-update';
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

    $rows = SV12AllView::getInstance()->findAll();

    foreach($rows as $row) {
      try {
      echo $row->id."\n";
      $sv12 = SV12Client::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
      foreach($sv12->mouvements as $etablissement_id => $mouvements) {
        foreach($mouvements as $mouvement) {
          $mouvement->date = SV12Client::getInstance()->buildDate($sv12->periode);

          if(!$mouvement->detail_identifiant) {

            continue;
          }

          
          $vrac = VracClient::getInstance()->find($mouvement->detail_identifiant, acCouchdbClient::HYDRATE_JSON);
          if(!$vrac) {
            throw new sfException("trouve pas le contrat %s", $mouvement->detail_identifiant);
          }
         
          $mouvement->detail_libelle = $vrac->numero_archive;
          $sv12_contrat = $sv12->contrats->{$mouvement->vrac_numero};

          if($sv12_contrat->vendeur_identifiant == $etablissement_id) {
            $mouvement->vrac_destinataire = $sv12->declarant->nom;
          }

          if($sv12->identifiant == $etablissement_id) {
            $mouvement->vrac_destinataire = $sv12_contrat->vendeur_nom;
          }
        }
      }

      SV12Client::getInstance()->storeDoc($sv12);
      } catch (Exception $e) {
        echo sprintf("%s\n", $e->getMessage());
        continue;
      }
    }
  }
}
