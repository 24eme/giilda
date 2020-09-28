<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maintenanceDRMMouvementsUpdateTask
 *
 * @author mathurin
 */
class maintenanceRevendicationFilterAOPIGPTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('id_revendication', sfCommandArgument::REQUIRED, 'REVENDICATION'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'revendication-filter-aopigp';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $id_revendication = $arguments['id_revendication'];
        if(!$id_revendication){
            throw new sfException("L'identifiant d'un document de revendication est necessaire");
        }

        $revendication = RevendicationClient::getInstance()->find($id_revendication);
        $revendication->updateRegion();
        $revendication->cleanFromFilterIGPAOPAndRegion();
        $revendication->save();
    }


}
