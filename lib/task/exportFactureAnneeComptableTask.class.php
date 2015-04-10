<?php

class exportFactureAnneeComptableTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('annee_comptable', null, sfCommandArgument::REQUIRED, date('Y')),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'export';
        $this->name = 'facture-annee-comptable';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [facture-annee-comptable|INFO] export des lignes de facturation pour la compta annuelle

    [php symfony export:facture-annee-comptable|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $annee_comptable = $arguments['annee_comptable'];
        $export = new ExportCSV();
        $export->printHeaderAnneeComptable();

        ini_set('memory_limit', '2048M');
        set_time_limit(0);

        foreach (FactureEtablissementView::getInstance()->getAllFacturesForCompta() as $vfacture) {
            if(substr($vfacture->value[FactureEtablissementView::VALUE_DATE_FACTURATION],0,4) == $annee_comptable){
               
            $export->printFacture($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID],true);           
            }
            
        }
    }

}
