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
class VracUpdateRepartitionCVOTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
        new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, "id_doc"),
    ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'vrac';
    $this->name             = 'update-repartition-cvo';
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

    $cvo_repartition_origin = $vrac->cvo_repartition;
    $vrac->cvo_repartition = $vrac->calculCvoRepartition();

    if($cvo_repartition_origin != $vrac->cvo_repartition) {
        echo sprintf("Contrat %s CVO passé de %s à %s (code postal de l'acheteur %s)\n", $vrac->_id, $cvo_repartition_origin, $vrac->cvo_repartition, $vrac->acheteur->code_postal);
    }
    $vrac->save();
  }
}
