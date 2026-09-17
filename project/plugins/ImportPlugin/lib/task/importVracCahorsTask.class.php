<?php

class importVracCahorsTask extends importAbstractTask {

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'import';
        $this->name = 'vracs-cahors';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [importVrac|INFO] task does things.
Call it with:

  [php symfony importEtablissement|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $csvfile = fopen($arguments['file'], 'r');

        if (! $csvfile) {
            throw new sfException("Impossible d'ouvrir le fichier " . $arguments['csv']);
        }

        $configuration = ConfigurationClient::getInstance()->getCurrent();

        while(($data = fgetcsv($csvfile, 1000, ";")) !== false) {
            //print_r($data);
            $v = new Vrac();

            $v->numero_contrat = str_replace("00000", "0000", VracClient::getInstance()->buildNumeroContrat(substr($data[8], -4, 4), 0, 0, $data[11]));
            $v->constructId();
            $vToDelete = VracClient::getInstance()->find($v->_id);
            if($vToDelete && $vToDelete->exist("campagne_archive") && $vToDelete->campagne_archive == "UIVC") {
                VracClient::getInstance()->delete($vToDelete);
            } elseif($vToDelete) {
                echo "Le contrat ".$v->_id." existe déjà !\n";
                continue;
            }

            $v->numero_archive = sprintf("%05d", $data[0]);
            $v->add('campagne_archive', 'UIVC');
            $v->teledeclare = false;
            $v->type_transaction = VracClient::TYPE_TRANSACTION_VIN_VRAC;
            $produit = $configuration->identifyProductByLibelle("AOP Cahors Rouge");
            $v->setProduit($produit->getHash());
            if(is_numeric($data[2])) {
                $v->millesime = intval($data[2]);
            }
            $vendeur = EtablissementClient::getInstance()->findByCvi($data[3]);
            if(!$vendeur) {
                $vendeur = EtablissementClient::getInstance()->retrieveByName($data[4]);
            }
            if(!$vendeur) {
                echo "vendeur non trouve \"".$data[3]."\";\n";
                continue;
            }
            $v->vendeur_identifiant = $vendeur->_id;

            $acheteur = EtablissementClient::getInstance()->findByCvi($data[5]);
            if(!$acheteur) {
                $acheteur = EtablissementClient::getInstance()->retrieveByName($data[6]);
            }
            if(!$acheteur) {
                echo "acheteur non trouve \"".$data[5]."\";\n";
                continue;
            }
            $v->acheteur_identifiant = $acheteur->_id;
            $v->setInformations();
            $v->jus_quantite = floatval(str_replace(',', '.', $data[9]));
            $v->prix_unitaire = floatval(str_replace(',', '.', $data[10]));
            $v->prix_initial_unitaire = $v->prix_unitaire;
            $v->date_signature = preg_replace("|^(\d)/(\d)/(\d)$|","\3-\2-\1", $data[8]);
            $v->date_campagne = $v->date_signature;
            $v->date_visa = $v->date_signature;
            // $v->valide->date_saisie = $v->date_signature;
            $v->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
            $v->update();
            $v->save();
        }
    }

}
