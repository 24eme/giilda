<?php

class importMandatSepaTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
	      ));
        $this->addArguments(array(
          new sfCommandArgument('csv_file', sfCommandArgument::REQUIRED, "Fichier CSV contenant les informations bancaires (inspiration TIECPT du CIVA)")
        ));

        $this->namespace = 'import';
        $this->name = 'MandatSepa';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [import|INFO] task does things.
Call it with:

  [php symfony import|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        foreach (file($arguments['csv_file']) as $line) {
            if (preg_match('/^#/', $line)) {
                continue;
            }
            $datas = explode(",", preg_replace('/"/', '', str_replace("\n", "", $line)));
            if (!isset($datas[1])||$datas[1] != 'PC'||!$datas[14]) {
                echo sprintf("warning;données manquantes %s\n", implode(',',$datas));
                continue;
            }
            $societe = SocieteClient::getInstance()->find($datas[0]);
            $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($societe);
            if ($mandatSepa) {
                echo sprintf("infos;%s déjà existant dans la bdd\n", $mandatSepa->_id);
                continue;
            }
            $mandatSepa = MandatSepaClient::getInstance()->createDoc($societe);
            $mandatSepa->debiteur->iban = $datas[14];
            $mandatSepa->debiteur->bic = $datas[15];
            $mandatSepa->is_actif = 1;
            $mandatSepa->is_signe = 1;
            $mandatSepa->save();
            echo sprintf("succes;%s créé dans la bdd\n", $mandatSepa->_id);
        }

    }



}
