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
            $societe = SocieteClient::getInstance()->find($datas[0]);
            if (!$societe) {
                echo sprintf("warning;société non trouvée %s\n", implode(',',$datas));
                continue;
            }
            $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($societe);
            if ($datas[1] != 'PC' && $mandatSepa && $mandatSepa->is_actif) {
                $mandatSepa->is_actif = 0;
                $mandatSepa->save();
                echo sprintf("succes;Désactivation de %s\n", $mandatSepa->_id);
                continue;
            }
            if ($datas[1] != 'PC') {
                continue;
            }
            if ($mandatSepa && ($mandatSepa->debiteur->iban != $datas[14]||$mandatSepa->debiteur->bic != $datas[15])) {
                $mandatSepa->debiteur->iban = $datas[14];
                $mandatSepa->debiteur->bic = $datas[15];
                $mandatSepa->save();
                echo sprintf("succes;Mise à jour de %s\n", $mandatSepa->_id);
            }
            if ($mandatSepa && !$mandatSepa->is_actif) {
                $mandatSepa->is_actif = 1;
                $mandatSepa->save();
                echo sprintf("succes;Activation de %s\n", $mandatSepa->_id);
            }
            if ($mandatSepa) {
                continue;
            }
            $mandatSepa = MandatSepaClient::getInstance()->createDoc($societe);
            $mandatSepa->debiteur->iban = $datas[14];
            $mandatSepa->debiteur->bic = $datas[15];
            $mandatSepa->is_actif = 1;
            $mandatSepa->is_signe = 1;
            $mandatSepa->save();
            echo sprintf("succes;Création de %s\n", $mandatSepa->_id);
        }

    }



}