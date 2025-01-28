<?php

class daeImportTask extends sfBaseTask {

    protected function configure() {
      	$this->addArguments(array(
          new sfCommandArgument('csvfile', sfCommandArgument::REQUIRED, 'Fichier des dae'),
          new sfCommandArgument('etablissementid', sfCommandArgument::REQUIRED, 'Identifiant etablissement'),
          new sfCommandArgument('periode', sfCommandArgument::REQUIRED, 'Periode'),
      	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'application'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'dae';
        $this->name = 'import';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $file = $arguments['csvfile'];
        $identifiant = str_replace('ETABLISSEMENT-', '', $arguments['etablissementid']);
        $periode = $arguments['periode'].'-01';

        $daeCsvEdi = new DAEImportCsvEdi($file, $identifiant, $periode);
        $daeCsvEdi->checkCSV();

        if(!$daeCsvEdi->getCsvDoc()->hasErreurs()) {
            $nb = $daeCsvEdi->importCsv();
        }

        if(!$daeCsvEdi->getCsvDoc()->hasErreurs()) {
             $this->logSection("import", $nb." DAE importés avec succès", null, 'SUCCESS');
        } else {
            $this->logSection("import", "L'import a échoué :", null, 'ERROR');
            foreach ($daeCsvEdi->getCsvDoc()->erreurs as $erreur) {
                echo $erreur->num_ligne.' '.$erreur->csv_erreur.' '.$erreur->diagnostic."\n";
            }
        }
    }

}
