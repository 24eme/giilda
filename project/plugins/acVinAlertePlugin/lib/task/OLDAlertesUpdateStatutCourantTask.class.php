<?php

class AlertesUpdateStatutCourantTask extends sfBaseTask
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
    $this->name             = 'update-statut-courant';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [generateAlertes|INFO] task does things.
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
    			$alerte->save();
    			$this->logSection('alertes', $a->id.' updated successfully.');
    		} catch (Exception $e) {
    			$this->logSection('alertes', $a->id.' save failed.', null, 'ERROR');
    		}
    	} else {
    		$this->logSection('alertes', $a->id.' document doesn\'t exist.', null, 'ERROR');
    	}
    }
  }
}
