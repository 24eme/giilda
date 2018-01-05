<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlertesSetDateARelancerTask
 * @author mathurin
 */
class AlertesSetDateARelancerTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'alertes';
    $this->name             = 'set-date-arelancer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [AlertesSetDateARelancer|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $alertes = array_merge(AlerteHistoryView::getInstance()->findByTypeAndStatut("DRM_MANQUANTE","NOUVEAU"),AlerteHistoryView::getInstance()->findByTypeAndStatut("DRA_MANQUANTE","NOUVEAU"));
    foreach ($alertes as $a) {
    	if ($alerte = AlerteClient::getInstance()->find($a->id)) {
        if($alerte->isStatutNouveau() && $alerte->date_relance == "2018-01-08"){
          echo $alerte->id_document." Alerte à relancer => nouvelle date ".$alerte->date_relance ." = 2017-01-01 \n";
          $alerte->date_relance = "2017-01-01";
          $alerte->save();
        }
    }
  }
}
}
