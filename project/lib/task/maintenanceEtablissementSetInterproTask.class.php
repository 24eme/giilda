<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maintenanceEtablissementSetInterproTask
 *
 * @author mathurin
 */
class maintenanceEtablissementSetInterproTask extends sfBaseTask {

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('interpro', sfCommandArgument::REQUIRED, 'InterproName'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'etablissementSetInterpro';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenance:etablissementSetInterpro|INFO] set interpro field for etablissements.
Call it with:

  [php symfony maintenance:etablissementSetInterpro|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $interproName = $arguments['interpro'];

        $etablissementsInterproNull = EtablissementAllView::getInstance()->findByInterpro(null);
         echo "Début de la tache d'assignement des Interpro \n\n";
        foreach ($etablissementsInterproNull->rows as $etbView) {
            $etb = EtablissementClient::getInstance()->find($etbView->id);
            echo "Etablissement ".$etbView->id." sauvé avec une valeur interpro=".$interproName."\n";
            $etb->set('interpro',$interproName);
            $etb->save();
        }
         echo "Fin de la tache d'assignement des Interpro\n";
    }

}
