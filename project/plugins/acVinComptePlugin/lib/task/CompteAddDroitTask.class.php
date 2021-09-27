<?php

class CompteAddDroitTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'Document ID'),
            new sfCommandArgument('droit', sfCommandArgument::REQUIRED, 'droit'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'compte';
        $this->name             = 'add-droit';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [CompteAddDroitTask|INFO] task does things.
Call it with:

    [php symfony document:setvalue|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $compte = acCouchdbManager::getClient()->find($arguments['doc_id']);

        if(!$compte->isSocieteContact()) {
            return;
        }

        if($compte->hasDroit($arguments['droit'])) {
            return;
        }

        $droits = $compte->droits->toArray(true, false);

        $droits[] = $arguments['droit'];
        $compte->remove('droits');
        $compte->add('droits', $droits);

        $compte->save();

        echo $compte->_id.";".$arguments['droit'].";Droit ajouté\n";
    }
}
