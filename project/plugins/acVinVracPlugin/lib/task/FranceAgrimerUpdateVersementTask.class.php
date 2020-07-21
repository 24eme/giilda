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
class FranceAgrimerUpdateVersementTask extends sfBaseTask {


    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->addArguments(array(
         new sfCommandArgument('docid', sfCommandArgument::REQUIRED, "Document id"),
        ));

        $this->namespace = 'france-agrimer';
        $this->name = 'update-versement';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [france-agrimer:update-versement|INFO] task update
Call it with:

  [php symfony france-agrimer:update-versement|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $vrac = VracClient::getInstance()->find($arguments['docid']);
        if (!$vrac || $vrac->exist('versement_fa') || $vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE) {
            return;
        }
        if (!$vrac->updateVersementFa()) {
            return ;
        }
        echo $vrac->_id." updated\n";
        $vrac->save();
    }

}
