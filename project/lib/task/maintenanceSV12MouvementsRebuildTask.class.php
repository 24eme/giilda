<?php

class maintenanceSV12MouvementsRebuildTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('id', sfCommandArgument::REQUIRED, 'SV12'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'maintenance';
        $this->name = 'sv12-mouvements-rebuild';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $sv12 = SV12Client::getInstance()->find($arguments['id']);
        $sv12->clearMouvements();
        $sv12->generateMouvements();
        $sv12->save();
        echo $sv12->_id."\n";
    }

}
