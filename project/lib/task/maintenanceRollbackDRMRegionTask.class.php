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
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
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
    $revision = null;
    $revisionsInfos = acCouchdbManager::getClient()->getRevisions($this->document_id);
    $i=0;
    foreach($revisionsInfos as $revisionInfo) {
        $drm = acCouchdbManager::getClient()->getPreviousDoc($this->document_id, $revisionInfo[0]);
        if(!$drm) {
            break;
        }
        $ancienneRegionTrouve = false;
        foreach($drm->mouvements as $etablissement_id => $mouvements) {
            foreach($mouvements as $mouvement) {
                if(in_array($mouvement->region, array("TOURS", "NANTES", "ANGERS"))) {
                    $ancienneRegionTrouve = true;
                }
            }
        }
        if($ancienneRegionTrouve) {
            $revision = $drm->_rev;
            break;
        }
	$i++;
    }

    $drm = DRMClient::getInstance()->find($this->document_id);
    if(!$revision || $drm->_rev == $revision) {
        echo "$i;Le document $drm->_id n'a pas été rollbacké\n";
        return;
    }
    //$drm = acCouchdbManager::getClient()->rollBack($this->document_id, $revision);

    echo "$i;Le document $drm->_id a été rollbacké au contenu du doc de la revision $revision, il est maintenant à la revision $drm->_rev \n";
  }

}
