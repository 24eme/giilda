<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class maintenanceNumeroCourtierTask
 * @author mathurin
 */
class maintenanceVracRepartitionCVOTask extends sfBaseTask
{
  const CSV_COURTIER_ID = 0;
  const CSV_COURTIER_NUM = 1;
    
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
        new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, "id_doc"),
        new sfCommandArgument('cvo_repartition', sfCommandArgument::REQUIRED, "Répartition de la CVO : 50, 100, 100_ACHETEUR, 0"),
    ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'vrac-repartition-cvo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceVracRepartitionCVO|INFO] task does things.
Call it with:

  [php symfony maintenanceVracRepartitionCVO|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
  
    $vrac = VracClient::getInstance()->find($arguments['doc_id']);

    if(!$vrac) {

        throw new sfException(sprintf("ERREUR;Contrat introuvable %s", $arguments['doc_id']));
    }

    if(!isset(VracClient::$cvo_repartition[$arguments['cvo_repartition']])) {

        throw new sfException(sprintf("ERREUR;Repartition inexistante %s", $arguments['cvo_repartition']));
    }

    if (!is_null($vrac->volume_enleve) && $vrac->volume_enleve > 0) {
      throw new sfException(sprintf("ERREUR;Volume déjà enlevé : %s", $arguments['doc_id']));
    }

    $vrac->setCvoRepartition($arguments['cvo_repartition']);
    $vrac->save();
    $this->log(sprintf("Success: %s", $arguments['doc_id']));
  }
}