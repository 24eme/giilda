<?php

class importVracTask extends importAbstractTask {

    const CSV_LIGNE = 0;
    const CSV_NUMCONTRAT = 1;
    const CSV_CAMPAGNE = 2;
    const CSV_MOIS = 3;
    const CSV_NUMBORDEREAU = 4;
    const CSV_NUMORDREMOIS = 5;
    const CSV_DATE = 6;
    const CSV_LIEUSIGNATURE = 7;
    const CSV_DATESAISIE = 8;
    const CSV_IDVENDEUR = 9;
    const CSV_IDACHETEUR = 10;
    const CSV_IDCOURTIER = 11;
    const CSV_CHAIELABORATION = 12;
    const CSV_CHAILOGEMENT = 13;
    const CSV_MILLESIME = 14;
    const CSV_CODEVIN = 15; #code produit ?
    const CSV_PRIMEUR = 16;
    const CSV_BIO = 17;
    const CSV_PREPARE = 18;
    const CSV_VOLUME_PROPOSE_HL = 19;
    const CSV_VOLUME_ENLEVE_HL = 20;
    const CSV_PRIX_VENTE = 21;
    const CSV_CODECEPAGE = 22;
    const CSV_DEBUTRETIRAISON = 23;
    const CSV_FINRETIRAISON = 24;
    const CSV_FINRETIRAISONFORMATTE = 25;
    const CSV_CONDITIONRETIRAISON = 26;
    const CSV_OBSERVATIONS = 27;
    const CSV_CLAUSERESERVE = 28;
    const CSV_ACCOMPTE = 29;
    const CSV_SOLDEFACTURE = 30;
    const CSV_SOLDEVOLUMEFACTURE = 31;
    const CSV_CONTRATANNULE = 32;
    const CSV_CHIFFREAFFAIRE = 33;
    const CSV_EXCLUREV2 = 34;
    const CSV_VERIFONCAT = 35;
    const CSV_OUINON_O = "O";
    const CSV_OUINON_N = "N";
    const CSV_TYPE_PRODUIT_INDETERMINE = 0;
    const CSV_TYPE_PRODUIT_RAISINS = 1;
    const CSV_TYPE_PRODUIT_MOUTS = 2;
    const CSV_TYPE_PRODUIT_VIN_VRAC = 3;
    const CSV_TYPE_PRODUIT_TIRE_BOUCHE = 5;
    const CSV_TYPE_PRODUIT_VIN_LATTES = 6;
    const CSV_TYPE_PRODUIT_VIN_CRD = 7;
    const CSV_TYPE_PRODUIT_VIN_BOUTEILLE = 8;
    const CSV_UNITE_VOLUME_HL = 'hl';
    const CSV_UNITE_VOLUME_L = 'l';
    const CSV_UNITE_VOLUME_KG = 'kg';
    const CSV_UNITE_VOLUME_BARRIQUE = 'ba';
    const CSV_TYPE_CONTRAT_SORTIES_COOP = 'C';
    const CSV_TYPE_CONTRAT_PLURIANNUEL_PRIX_A_DEFINIR = 'D';
    const CSV_TYPE_CONTRAT_FITRANEG = 'F';
    const CSV_TYPE_CONTRAT_FERMAGE = 'M';
    const CSV_TYPE_CONTRAT_PLURIANNUEL = 'P';
    const CSV_TYPE_CONTRAT_QUINQUENNAL = 'Q';
    const CSV_TYPE_CONTRAT_PAS_TRANSACTION_FINANCIERE = 'T';
    const CSV_TYPE_CONTRAT_VINAIGRERIE = 'V';

    protected function configure() {
        // // add your own arguments here
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
        $this->name = 'vracs';
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

        set_time_limit(0);
        $i = 1;
        foreach (file($arguments['file']) as $line) {
            if (!preg_match('/^Ligne;Numéro Contrat/', $line)) {
                $data = str_getcsv($line, ';');
                $vrac = $this->importVrac($data);
                $vrac->save();
            }

            $i++;
        }
    }

    public function importVrac($line) {
        $type_transaction = $this->convertTypeTransaction($line);

        if (!$type_transaction) {

            throw new sfException(sprintf("Le type de transaction est inexistant", $type_transaction));
        }

        $v = VracClient::getInstance()->findByNumContrat($this->constructNumeroContrat($line));

        if (!$v) {
            $v = new Vrac();
            $v->numero_contrat = $this->constructNumeroContrat($line);
        }

        $v->numero_archive = $this->getNumeroArchive($line);

        $date = $this->getDateCampagne($line);
        $v->date_signature = $date->format('Y-m-d');
        $v->date_campagne = $date->format('Y-m-d');
        $v->valide->date_saisie = $this->convertToDateObject($line[self::CSV_DATESAISIE], true)->format('Y-m-d');

        $v->vendeur_identifiant = $this->getIdentifiantVendeur($line);
        $v->representant_identifiant = $v->vendeur_identifiant;
        $v->acheteur_identifiant = $this->getIdentifiantAcheteur($line);
        $v->mandataire_identifiant = null;

        if ($line[self::CSV_IDCOURTIER]) {
            $v->mandataire_identifiant = $this->getIdentifiantCourtier($line);
        }

        $v->produit = $this->getHash(101);//$line[self::CSV_CODEVIN]);

        $v->millesime = $line[self::CSV_MILLESIME] ? (int) $line[self::CSV_MILLESIME] : null;
        $v->categorie_vin = VracClient::CATEGORIE_VIN_GENERIQUE;

        if (!$v->getVendeurObject()) {

            throw new sfException(sprintf("L'etablissement %s n'existe pas", $v->vendeur_identifiant));
        }

        if (!$v->getAcheteurObject()) {

            throw new sfException(sprintf("L'etablissement %s n'existe pas", $v->acheteur_identifiant));
        }

        if (!$v->mandataire_identifiant || !$v->getMandataireObject()) {
            $v->mandataire_identifiant = null;
        } else {
            $v->mandataire_exist = 1;
        }

        $v->type_transaction = $type_transaction;

        if (in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE))) {
            $v->bouteilles_contenance_volume = $line[self::CSV_COEF_CONVERSION_PRIX] * 0.01;
            $v->bouteilles_contenance_libelle = $this->getBouteilleContenanceLibelle($v->bouteilles_contenance_volume);
            $v->bouteilles_quantite = (int) ($this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]) / $v->bouteilles_contenance_volume);
        } elseif (in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_MOUTS,
                    VracClient::TYPE_TRANSACTION_VIN_VRAC))) {
            $v->jus_quantite = $this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]);
        } elseif (in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_RAISINS))) {
            if ($line[self::CSV_UNITE_VOLUME] == 'kg') {
                $v->raisin_quantite = $this->convertToFloat($line[self::CSV_VOLUME]);
            } else {
                $v->raisin_quantite = $this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL] * $this->getDensite($line) * 100);
            }
        }

        $v->volume_propose = $this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]);

        if ($v->volume_propose == 0) {

            throw new sfException('Le Volume proposé est O');
        }

        $v->volume_enleve = $this->convertToFloat($line[self::CSV_VOLUME_ENLEVE_HL]);

        $v->prix_initial_unitaire = $this->convertToFloat($this->calculPrixInitialUnitaire($v, $line));

        $v->prix_initial_unitaire_hl = $this->convertToFloat($line[self::CSV_PRIX_VENTE]);
        $v->prix_initial_total = $v->prix_initial_unitaire_hl * $v->volume_propose;

        $v->type_contrat = $this->convertTypeContrat($line[self::CSV_EXCLUREV2]);

        $v->prix_variable = 0;

        $v->prix_unitaire = $v->prix_initial_unitaire;
        $v->prix_unitaire_hl = $v->prix_initial_unitaire_hl;
        $v->prix_total = $v->prix_initial_total;


        $v->attente_original = 0;

        $v->label = array();
        if ($this->convertOuiNon($line[self::CSV_BIO])) {
            $v->label->add(null, 'agriculture_biologique');
        }

        $v->_set('cvo_repartition', VracClient::CVO_REPARTITION_50_50);


        $v->valide->statut = $line[self::CSV_CONTRATANNULE] == "O" ? VracClient::STATUS_CONTRAT_ANNULE : VracClient::STATUS_CONTRAT_NONSOLDE;

        $v->setInformations();

        return $v;
    }

    protected function getDateCampagne($line) {

        return $this->convertToDateObject($line[self::CSV_DATE], true);
    }

    protected function getIdentifiantVendeur($line) {

        return "752370010001";

        $idChaiEtb = $line[self::CSV_CHAIELABORATION];
        /*
         * TODO : récupérer l'id Chai ici et du coup le bon ETB
         */
        return sprintf('%s%02d', $line[self::CSV_IDVENDEUR], "01");
    }

    protected function getIdentifiantAcheteur($line) {
        return "312370080001";
        /*
         * TODO : récupérer l'id Chai ici et du coup le bon ETB
         */
        return sprintf('%s%02d', $line[self::CSV_IDACHETEUR], 1);
    }

    protected function getIdentifiantCourtier($line) {
        return "242370090001";
        /*
         * TODO : récupérer l'id Chai ici et du coup le bon ETB
         */
        return sprintf('%s%02d', $line[self::CSV_CODE_COURTIER], 1);
    }

    protected function getDensite($line) {
        if ($line[self::CSV_UNITE_PRIX_VENTE] == 'kg' && $line[self::CSV_COEF_CONVERSION_PRIX]) {
            return $line[self::CSV_COEF_CONVERSION_PRIX];
        }

        $hash = $this->getHash($line[self::CSV_CODE_APPELLATION]);
        if (preg_match('/appellations\/CLO\//', $hash)) {
            return 1.5;
        } else {
            return 1.3;
        }
    }

    protected function calculPrixUnitaire($vrac, $line, $prix_unitaire) {
        if (in_array($vrac->type_transaction, array(VracClient::TYPE_TRANSACTION_RAISINS)) && $line[self::CSV_UNITE_PRIX_VENTE] == 'hl') {

            return $prix_unitaire * $vrac->volume_propose / $vrac->raisin_quantite;
        }

        return $prix_unitaire;
    }

    protected function calculPrixInitialUnitaire($vrac, $line) {

        return $line[self::CSV_PRIX_VENTE];
    }

    protected function calculPrixDefinitifUnitaire($vrac, $line) {

        return $this->calculPrixUnitaire($vrac, $line, $line[self::CSV_PRIX_DEFINITIF]);
    }

    protected function convertTypeTransaction($line) {


//        $type_transactions = array(
//            self::CSV_TYPE_PRODUIT_INDETERMINE => null,
//            self::CSV_TYPE_PRODUIT_RAISINS => VracClient::TYPE_TRANSACTION_RAISINS,
//            self::CSV_TYPE_PRODUIT_MOUTS => VracClient::TYPE_TRANSACTION_MOUTS,
//            self::CSV_TYPE_PRODUIT_VIN_VRAC => VracClient::TYPE_TRANSACTION_VIN_VRAC,
//            self::CSV_TYPE_PRODUIT_TIRE_BOUCHE => VracClient::TYPE_TRANSACTION_VIN_VRAC,
//            self::CSV_TYPE_PRODUIT_VIN_LATTES => VracClient::TYPE_TRANSACTION_VIN_VRAC,
//            self::CSV_TYPE_PRODUIT_VIN_CRD => VracClient::TYPE_TRANSACTION_VIN_VRAC,
//            self::CSV_TYPE_PRODUIT_VIN_BOUTEILLE => VracClient::TYPE_TRANSACTION_VIN_VRAC,
//        );


        return VracClient::TYPE_TRANSACTION_VIN_VRAC;
    }

    protected function convertTypeContrat($type) {
        $type_contrats = array(
            self::CSV_OUINON_O => VracClient::TYPE_CONTRAT_SPOT,
            self::CSV_OUINON_N => VracClient::TYPE_CONTRAT_PLURIANNUEL
        );

        if (array_key_exists($type, $type_contrats)) {

            return $type_contrats[$type];
        }

        return VracClient::TYPE_CONTRAT_SPOT;
    }

    protected function getBouteilleContenanceLibelle($v) {
        $contenances = array("0.00375" => '37 cl',
            "0.005" => '50 cl',
            "0.0075" => '75 cl',
            "0.01" => '1 L',
            "0.015" => '1.5 L',
            "0.03" => '3 L',
            "0.05" => '5 L',
            "0.06" => '6 L');
        $v = $v . "";
        if (array_key_exists($v, $contenances)) {
            return $contenances[$v];
        }

        throw new sfException(sprintf('Contenance %s introuvable', $v));
    }

    protected function constructNumeroContrat($line) {
        return $this->convertToDateObject($line[self::CSV_DATE], true)->format('Y') . $this->getNumeroArchive($line);
    }

    protected function getNumeroArchive($line) {

        return sprintf("%05d", $line[self::CSV_NUMCONTRAT]);
    }

}
