<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlertesSetLastDateModificationTask
 * @author mathurin
 */
class AlertesSetLastDateModificationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'alertes';
    $this->name             = 'set-last-date-modification';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [setLastDateModificationAlertes|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $alertes = AlerteHistoryView::getInstance()->getAllHistory();
    foreach ($alertes as $a) {
    	if ($alerte = AlerteClient::getInstance()->find($a->id)) {
    		try {
                        $date = $alerte->getStatut()->date;
                        $alerte->add('date_dernier_statut', $date);
    			$alerte->save();
    			$this->logSection('alertes', $a->id.' updated successfully with date '.$date.'.');
    		} catch (Exception $e) {
    			$this->logSection('alertes', $a->id.' save failed.', null, 'ERROR');
    		}
    	} else {
    		$this->logSection('alertes', $a->id.' document doesn\'t exist.', null, 'ERROR');
    	}
    }
  }
}
