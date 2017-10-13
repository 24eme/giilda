<?php

class maintenanceRollbackDRMRegionTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('document_id', sfCommandArgument::REQUIRED, 'Document ID to change'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'rollback-drm-region';
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
    $this->document_id = $arguments['document_id'];
    $drm = DRMClient::getInstance()->find($this->document_id);
    $drmPrev = acCouchdbManager::getClient()->getPreviousDoc($drm->_id);
    $revision = null;
    while($drmPrev) {
        $ancienneRegionTrouve = false;
        foreach($drmPrev->mouvements as $etablissement_id => $mouvements) {
            foreach($mouvements as $mouvement) {
                if(in_array($mouvement->region, array("TOURS", "NANTES", "ANGERS"))) {
                    $ancienneRegionTrouve = true;
                }
            }
        }
        if($ancienneRegionTrouve) {
            $revision = $drmPrev->_rev;
            break;
        }
        try {
            $drmPrev = acCouchdbManager::getClient()->getPreviousDoc($drm->_id);
        } catch(Exception $e) {

            $drmPrev = null;
        }
    }

    if(!$revision || $drm->_rev == $revision) {
        echo "Le document $drm->_id n'a pas été rollbacké\n";
        return;
    }
    acCouchdbManager::getClient()->rollBackDoc($drm, $revision);

    echo "Le document $drm->_id a été rollbacké au contenu du doc de la revision $revision il est maintenant à la revision $drm->_rev \n";
  }


}
