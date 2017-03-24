<?php

class maintenanceEtablissementRepareContactTask extends sfBaseTask
{

    protected function configure()
    {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, "Societe document id"),
        ));
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
          // add your own options here
        ));

        $this->namespace        = 'maintenance';
        $this->name             = 'etablissement-repare-contacts';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $etablissement = EtablissementClient::getInstance()->find($arguments['doc_id'], acCouchdbClient::HYDRATE_JSON);
        $societe = SocieteClient::getInstance()->find($etablissement->id_societe, acCouchdbClient::HYDRATE_JSON);
        if(!isset($societe->contacts->{$etablissement->compte})) {
            $societe = SocieteClient::getInstance()->find($etablissement->id_societe);
            echo $etablissement->_id.";PAS OK\n";
            $societe->contacts->add($etablissement->compte);
            $societe->save();
        } else {
            echo $etablissement->_id.";OK\n";
        }
    }

}
