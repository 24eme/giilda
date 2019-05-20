<?php

class maintenanceAnnuaireLibelleReconstructionTask extends sfBaseTask
{
    protected function configure()
    {
        // add your own arguments here
        $this->addArguments(array(
          new sfCommandArgument('annuaire', sfCommandArgument::REQUIRED, 'ID de l\'annuaire'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            // add your own options here
        ));

        $this->namespace        = 'maintenance';
        $this->name             = 'AnnuaireLibelleReconstruction';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenance:AnnuaireLibelleReconstruction|INFO] task does things.
Call it with:

  [php symfony maintenance:AnnuaireLibelleReconstruction|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $etablissements = EtablissementClient::getInstance();

        $annuaire = $arguments['annuaire'];
        $annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($annuaire);

        foreach (array_keys(AnnuaireClient::getAnnuaireTypes()) as $type) {
            foreach ($annuaire->get($type) as $key => $etab) {
                $tier = $etablissements->find($key);
                $annuaire->addTier($tier, $type);
            }
        }

        $annuaire->save();
    }
}
