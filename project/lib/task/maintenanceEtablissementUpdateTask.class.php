<?php

class maintenanceEtablissementUpdateTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'etablissement-update';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceEtablissementUpdateTask|INFO] task does things.
Call it with:

  [php symfony maintenanceEtablissementUpdateTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $viewClient = EtablissementAllView::getInstance();
        $etablissementsView = $viewClient->getClient()->startkey()
                            ->endkey(array(array()))
			    ->reduce(false)
			    ->getView($viewClient->getDesign(), $viewClient->getView());

        foreach ($etablissementsView->rows as $etablissementView) {

            $etablissement = EtablissementClient::getInstance()->find($etablissementView->id);
            if (!$etablissement) {
                echo "L'établissement $etablissementView->id n'a pas été trouvé dans la base! \n";
            }
            echo $etablissement->_id." va être sauvé   >>>>>>>>>>>>  ";
            
            try {
                $etablissement->save();                
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            echo $etablissement->_id . " $etablissement->statut saved! \n";
        }
    }

}
