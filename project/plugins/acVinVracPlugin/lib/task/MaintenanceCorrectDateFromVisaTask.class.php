<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MaintenanceTransfertChaiTask
 *
 * @author mathurin
 */
class MaintenanceCorrectDateFromVisaTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('id', sfCommandArgument::REQUIRED, "Vrac ID"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'correctDateFromVisa';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
  [php symfony maintenance:orrectDateFromVisa VRAC-20XXXXXXX|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $id = $arguments['id'];
        $vrac = VracClient::getInstance()->find($id);
        $vrac->date_campagne = $vrac->date_visa.'T12:00:00+02:00';
        $vrac->date_signature = $vrac->date_visa;
        $vrac->enlevement_date = $vrac->date_signature;
        $vrac->valide->date_saisie = $vrac->date_campagne;
        $vrac->save();

    }



}
