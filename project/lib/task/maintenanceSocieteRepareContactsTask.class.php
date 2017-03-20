<?php

class maintenanceSocieteRepareContactsTask extends sfBaseTask
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
        $this->name             = 'societe-repare-contacts';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $societe = SocieteClient::getInstance()->find($arguments['doc_id']);

        $contacts_to_delete = array();

        foreach($societe->contacts as $id => $contact) {
            $compte = CompteClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
            if($compte) {
                continue;
            }

            $contacts_to_delete[$id] = $id;
        }

        foreach($contacts_to_delete as $id) {
            echo $id."\n";
            $societe->contacts->remove($id);
        }

        $societe->save();
    }

}
