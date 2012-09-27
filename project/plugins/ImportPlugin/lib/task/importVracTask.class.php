<?php

class importVracTask extends sfBaseTask
{

  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_NUMERO_CONTRAT = 2;
  const CSV_DATE_ENREGISTREMENT = 3;
  const CSV_CODE_RECETTE_LOCALE = 4;
  const CSV_CODE_VITICULTEUR = 5;
  const CSV_CODE_CHAI_CAVE = 6;
  const CSV_CODE_NEGOCIANT = 7;
  const CSV_CODE_COURTIER = 8;
  const CSV_CODE_APPELLATION = 9;
  const CSV_TYPE_PRODUIT = 10;
  const CSV_MILLESIME = 11;
  const CSV_COTISATION_CVO_NEGOCIANT = 12;
  const CSV_COTISATION_CVO_VITICULTEUR = 13;
  const CSV_VOLUME = 14;
  const CSV_UNITE_VOLUME = 15;
  const CSV_COEF_CONVERSION_VOLUME = 16;
  const CSV_MODE_CONVERSION_VOLUME = 17;
  const CSV_VOLUME_PROPOSE_HL = 18;
  const CSV_VOLUME_ENLEVE_HL = 19;
  const CSV_PRIX_VENTE = 20;
  const CSV_CODE_DEVISE = 21;
  const CSV_UNITE_PRIX_VENTE = 22;
  const CSV_COEF_CONVERSION_PRIX = 23;
  const CSV_MODE_CONVERSION_PRIX = 24;
  const CSV_PRIX_AU_LITRE = 25;
  const CSV_CONTRAT_SOLDE = 26;
  const CSV_DATE_SIGNATURE_OU_CREATION = 27;
  const CSV_DATE_DERNIERE_MODIFICATION = 28;
  const CSV_CODE_SAISIE = 29;
  const CSV_DATE_LIVRAISON = 30;
  const CSV_CODE_MODE_PAIEMENT = 31;
  const CSV_COMPOSTAGE = 32;
  const CSV_TYPE_CONTRAT = 33;
  const CSV_ATTENTE_ORIGINAL = 34;
  const CSV_CATEGORIE_VIN = 35;
  const CSV_CEPAGE = 36;
  const CSV_MILLESIME_ANNEE = 37;
  const CSV_PRIX_HORS_CVO = 38;
  const CSV_PRIX_CVO_INCLUSE = 39;
  const CSV_TAUX_CVO_GLOBAL = 40;

  const CSV_CIAPL_DOSSIER = 41;
  const CSV_CIAPL_CAMPAGNE = 42;
  const CSV_CIAPL_CODE = 43;
  const CSV_CIAPL_LIBELLE = 44;
  const CSV_CIAPL_CODE_COMPTABLE_ANALYTICS = 45;
  const CSV_CIAPL_CODE_COMPTABLE_VENTE = 46;
  const CSV_CIAPL_DATE_CREATION = 47;
  const CSV_CIAPL_DATE_DERNIERE_MODIFICATION = 48;
  const CSV_CIAPL_STQZ = 49;
  const CSV_CIAPL_CEPAGE = 50;
  const CSV_CIAPL_CODE_STATS = 51;
  const CSV_CIAPL_SUSPENDU = 52;
  const CSV_CIAPL_COULEUR = 53;
  const CSV_CIAPL_SUR_LIE = 54;
  const CSV_CIAPL_AGREMENT_LABEL = 55;
  const CSV_CIAPL_REGION_VITICOLE = 56;
  const CSV_CIAPL_MOUSSEUX = 57;

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

  protected static $months_fr = array(
    "août" => "08",
    "avr" => "04",
    "déc" => "12",
    "févr" => "02",
    "janv" => "01",
    "juil" => "07",
    "juin" => "06",
    "mai" => "05",
    "mars" => "03",
    "nov" => "11",
    "oct" => "10",
    "sept" => "09",
  );

  protected $produits_hash = null;

  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'vrac';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importVrac|INFO] task does things.
Call it with:

  [php symfony importEtablissement|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    set_time_limit(0);

    foreach(file($arguments['file']) as $line) {
    	$data = str_getcsv($line, ';');
    	$this->importVrac($data);
    }

  }

  public function importVrac($line) {

        $type_transaction = $this->convertTypeTransaction($line[self::CSV_TYPE_PRODUIT]);   

        if (!$type_transaction) {
          return;
        }

        $hash = $this->getHash($line);

        if (!isset($hash)) {
          $this->logSection('Produit hash not found', $line[self::CSV_CODE_APPELLATION], null, 'ERROR');
          return;
        } 
        
        $v = VracClient::getInstance()->findByNumContrat($this->constructNumeroContrat($line), acCouchdbClient::HYDRATE_JSON);
        
        if (!$v) {
          $v = new Vrac();
          $v->numero_contrat = $this->constructNumeroContrat($line);
        }

        $v->label = array();

        $date = $this->getDateCreationObject($line[self::CSV_DATE_SIGNATURE_OU_CREATION]);
        $v->date_signature =  $date->format('Y-m-d');
        $v->date_stats =  $date->format('Y-m-d');
        $v->valide->date_saisie = $date->format('Y-m-d');

        $v->vendeur_identifiant = 'ETABLISSEMENT-'.$line[self::CSV_CODE_VITICULTEUR];
        $v->acheteur_identifiant = 'ETABLISSEMENT-'.$line[self::CSV_CODE_NEGOCIANT];
        $v->mandataire_identifiant = null;

        if ($line[self::CSV_CODE_COURTIER]) {
          $v->mandataire_identifiant   = 'ETABLISSEMENT-'.$line[self::CSV_CODE_COURTIER];
        }

        $v->produit = $hash;

        if($line[self::CSV_CIAPL_SUR_LIE] == "O") {
          $v->label->add(null, "LIE");
        }

        $v->millesime = $line[self::CSV_MILLESIME_ANNEE] ? (int)$line[self::CSV_MILLESIME_ANNEE] : null;

        if (!$v->getVendeurObject() || !$v->getAcheteurObject()) {
          $this->logSection("Les etablissements n'existes pas",  $line[self::CSV_CIAPL_REGION_VITICOLE]."@".$v->numero_contrat."V:".$v->vendeur_identifiant.";A:".$v->acheteur_identifiant, null, 'ERROR');
          return;
        }

        if (!$v->mandataire_identifiant || !$v->getMandataireObject()) {
          $v->mandataire_identifiant = null;
        } else {
          $v->mandataire_exist = 1;
        }

        $v->type_transaction = $type_transaction;
        
        if (in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE))) {
          if(preg_match('/^b[0-9]{1}$/', $line[self::CSV_UNITE_PRIX_VENTE])) {
          	$v->bouteilles_contenance_volume = $this->convertToFloat($line[self::CSV_COEF_CONVERSION_PRIX]);
            $v->bouteilles_contenance_libelle = $this->getBouteilleContenanceLibelle($v->bouteilles_contenance_volume);
          	$v->bouteilles_quantite = (int)round($this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]) / $v->bouteilles_contenance_volume);
      	  }
        } elseif(in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_MOUTS,
                                                      VracClient::TYPE_TRANSACTION_VIN_VRAC))) {
          	$v->jus_quantite = $this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]);
        } elseif(in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_RAISINS))) {
          	$v->raisin_quantite = round($this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL] * $this->getDensite($line)), 2);
        }

        $v->volume_propose = $this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]);

        $v->volume_enleve = $this->convertToFloat($line[self::CSV_VOLUME_ENLEVE_HL]);

        $v->prix_unitaire = round($this->convertToFloat($line[self::CSV_PRIX_AU_LITRE]), 2);

        $v->type_contrat = $this->convertTypeContrat($line[self::CSV_TYPE_CONTRAT]);

        $v->prix_variable = 0;

        $v->cvo_nature = $this->convertCvoNature($line[self::CSV_TYPE_CONTRAT]);

        $v->valide->statut = $line[self::CSV_CONTRAT_SOLDE] == "O" ? VracClient::STATUS_CONTRAT_SOLDE : VracClient::STATUS_CONTRAT_NONSOLDE;

        $v->setInformations();

        $v->update(); 

        $v->save();

        //$this->logSection("Creation", $v->numero_contrat);
  }

  protected function convertToFloat($number) {

  	return str_replace(",", ".", $number) * 1;
  }

  protected function getDensite($line) {
  	if($line[self::CSV_UNITE_PRIX_VENTE] == 'kg') {
  		return $line[self::CSV_COEF_CONVERSION_PRIX];
  	}

  	if (preg_match('/CREMANT DE LOIRE/i', $line[self::CSV_CIAPL_LIBELLE])) {
  		return 1.5;
  	} else {
  		return 1.3;
  	}
  }

  protected function getDateCreationObject($date) {
    
    if (!preg_match('/^([0-9]{2})-([a-zûé]+)-([0-9]{2})$/', $date, $matches)) {
      $this->logSection('Date format error', $date, null, 'ERROR');
    }

    return new DateTime(sprintf('%d-%d-%d', $matches[3], self::$months_fr[$matches[2]], $matches[1]));
  }

  protected function constructNumeroContrat($line) {

    return $this->getDateCreationObject($line[self::CSV_DATE_SIGNATURE_OU_CREATION])->format('Ymd') . sprintf("%04d", $line[self::CSV_NUMERO_CONTRAT]);
  }

  protected function convertTypeTransaction($type) {
    $type_transactions = array(
      self::CSV_TYPE_PRODUIT_INDETERMINE => null,
      self::CSV_TYPE_PRODUIT_RAISINS => VracClient::TYPE_TRANSACTION_RAISINS,
      self::CSV_TYPE_PRODUIT_MOUTS => VracClient::TYPE_TRANSACTION_MOUTS,
      self::CSV_TYPE_PRODUIT_VIN_VRAC => VracClient::TYPE_TRANSACTION_VIN_VRAC,
      self::CSV_TYPE_PRODUIT_TIRE_BOUCHE => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
      self::CSV_TYPE_PRODUIT_VIN_LATTES => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
      self::CSV_TYPE_PRODUIT_VIN_CRD => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
      self::CSV_TYPE_PRODUIT_VIN_BOUTEILLE => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
    );

    if (array_key_exists($type, $type_transactions)) {

      return $type_transactions[$type];
    }

    return null;
  }

  protected function convertTypeContrat($type) {
    $type_contrats = array(
        self::CSV_TYPE_CONTRAT_SORTIES_COOP => VracClient::TYPE_CONTRAT_SPOT,
        self::CSV_TYPE_CONTRAT_PLURIANNUEL_PRIX_A_DEFINIR => VracClient::TYPE_CONTRAT_PLURIANNUEL,
        self::CSV_TYPE_CONTRAT_FITRANEG => VracClient::TYPE_CONTRAT_SPOT,
        self::CSV_TYPE_CONTRAT_FERMAGE => VracClient::TYPE_CONTRAT_SPOT,
        self::CSV_TYPE_CONTRAT_PLURIANNUEL => VracClient::TYPE_CONTRAT_PLURIANNUEL,
        self::CSV_TYPE_CONTRAT_QUINQUENNAL => VracClient::TYPE_CONTRAT_PLURIANNUEL,
        self::CSV_TYPE_CONTRAT_PAS_TRANSACTION_FINANCIERE => VracClient::TYPE_CONTRAT_SPOT,
        self::CSV_TYPE_CONTRAT_VINAIGRERIE => VracClient::TYPE_CONTRAT_SPOT,
    );

    if (array_key_exists($type, $type_contrats)) {

      return $type_contrats[$type];
    }

    return null;
  }

  protected function convertCvoNature($type) {
    $type_contrats = array(
        self::CSV_TYPE_CONTRAT_PAS_TRANSACTION_FINANCIERE => VracClient::CVO_NATURE_NON_FINANCIERE,
        self::CSV_TYPE_CONTRAT_VINAIGRERIE => VracClient::CVO_NATURE_VINAIGRERIE,
    );

    if (array_key_exists($type, $type_contrats)) {

      return $type_contrats[$type];
    }

    return VracClient::CVO_NATURE_MARCHE_DEFINITIF;
  }

  protected function convertOuiNon($indicateur) {

    return (int) ($indicateur == 'O');
  }

  private function getHash($line) 
  {
    $produits_hash = $this->getProduitsHash();

    if (!array_key_exists($line[self::CSV_CODE_APPELLATION]*1, $produits_hash)) {
      
      return null;
    }

    return $produits_hash[$line[self::CSV_CODE_APPELLATION]*1];
  }

  private function couleurKeyToCode($key) {
    $correspondances = array(1 => "rouge",
                             2 => "rose",
                             3 => "blanc");

    if (!isset($correspondances[$key])) {
      throw new Exception("Couleur pas connue $key");
    }
    return $correspondances[$key];
  }

  private function getKey($key, $withDefault = false) 
  {
    if ($withDefault) {
      return ($key)? $key : Configuration::DEFAULT_KEY;
    } 
    if (!$key) {
      throw new Exception('La clé "'.$key.'" n\'est pas valide');
    }
    return $key;
  }

  protected function getBouteilleContenanceLibelle($v) {
        $contenances = array("0.0075" => '75 cl',
                            "0.01" => '1 L',
                            "0.015" => '1.5 L',
                            "0.03" => '3 L',
                            "0.06" => '6 L');
        $v = $v."";
        if (array_key_exists($v, $contenances)) {
          return $contenances[$v];
        }

        return null;
  } 

  protected function getProduitsHash() {
    if (is_null($this->produits_hash)) {
      $this->produits_hash =  ConfigurationClient::getCurrent()->declaration->getProduitsHashByCodeProduit('INTERPRO-inter-loire');
    }

    return $this->produits_hash;
  }
}
