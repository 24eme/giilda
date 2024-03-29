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

            if (!in_array($datas[1], array('PC', 'CB'))) {
                continue;
            }

            $societe = SocieteClient::getInstance()->find($datas[0]);
            if (!$societe) {
                echo sprintf("warning;société non trouvée %s\n", implode(',',$datas));
                continue;
            }

            $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($societe);

            if(!$datas[14] && $mandatSepa) {
                echo "Manda supprimé : ".$mandatSepa->_id."\n";
                $mandatSepa->delete();
                continue;
            }

            if(!$datas[14]) {
                continue;
            }

            if(!$mandatSepa) {
                $mandatSepa = MandatSepaClient::getInstance()->createDoc($societe);
            }

            $mandatSepa->debiteur->banque_nom = $datas[2];
            $mandatSepa->debiteur->banque_commune = $datas[3];
            $mandatSepa->debiteur->iban = $datas[14];
            $mandatSepa->debiteur->bic = $datas[15];
            $mandatSepa->is_actif = 0;
            $mandatSepa->is_signe = 0;

            if ($datas[1] == 'PC') {
                $mandatSepa->is_actif = 1;
                $mandatSepa->is_signe = 1;
            }

            $isNew = $mandatSepa->isNew();
            $saved = $mandatSepa->save();
            if($isNew) {
                echo sprintf("success;Création de %s\n", $mandatSepa->_id);
            } elseif($saved) {
                echo sprintf("success;Modification de %s\n", $mandatSepa->_id);
            }

        }

    }



}
