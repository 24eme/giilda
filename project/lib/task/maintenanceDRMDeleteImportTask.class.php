<?php

class maintenanceDRMDeleteImportTask extends sfBaseTask
{

    protected function configure()
    {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, "DRM document id"),
        ));
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
          // add your own options here
        ));

        $this->namespace        = 'maintenance';
        $this->name             = 'drm-delete-import';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $drm = DRMClient::getInstance()->find($arguments['doc_id']);

        if(!$drm) {
            return;
        }

        if($drm->type_creation != "IMPORT") {
            return;
        }

        foreach ($drm->getDetailsAvecCreationVracs() as $details) {
            foreach ($details as $keyVrac => $vracCreation) {
                $vrac = $vracCreation->getVrac();
                if(!$vrac || $vrac->isNew()) {
                    continue;
                }
                echo "Suppression du contrat ".$vrac->_id."\n";
                VracClient::getInstance()->delete($vrac);
            }
        }

        DRMClient::getInstance()->delete($drm);

        echo "La DRM ".$drm->_id." a été supprimée\n";
    }

}
