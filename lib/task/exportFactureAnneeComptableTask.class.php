<?php

class exportFactureAnneeComptableTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('annee_mois_debut', null, sfCommandArgument::REQUIRED, date('Y-m')),
            new sfCommandArgument('annee_mois_fin', null, sfCommandArgument::REQUIRED, date('Y-m')),
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
        $annee_mois_debut = $arguments['annee_mois_debut'];
        $annee_mois_fin = $arguments['annee_mois_fin'];

        ini_set('memory_limit', '2048M');
        set_time_limit(0);

        $annee_mois_debutStr = str_replace('-','', $annee_mois_debut);
        $annee_mois_finStr = str_replace('-','', $annee_mois_fin);

        
        $export = new ExportCSV();
        $export->printHeaderAnneeComptable();
        foreach (FactureEtablissementView::getInstance()->getAllFacturesForCompta() as $vfacture) {
            $factureAnnee = substr($vfacture->value[FactureEtablissementView::VALUE_DATE_FACTURATION], 0, 4);
            $factureMois = substr($vfacture->value[FactureEtablissementView::VALUE_DATE_FACTURATION], 5, 2);
            $factureAnneeMoisStr = $factureAnnee.$factureMois;
            if (($annee_mois_debutStr <= $factureAnneeMoisStr) && ($factureAnneeMoisStr <= $annee_mois_finStr)){               
                $export->printFacture($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID], true);
            }
        }
    }

}
