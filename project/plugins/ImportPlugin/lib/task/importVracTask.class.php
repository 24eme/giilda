<?php

class importVracTask extends importAbstractTask
{

  const CSV_TRI = 0;
  const CSV_DOSSIER = 1;
  const CSV_CAMPAGNE = 2;
  const CSV_NUMERO_CONTRAT = 3;
  const CSV_DATE_ENREGISTREMENT = 4;
  const CSV_CODE_RECETTE_LOCALE = 5;
  const CSV_CODE_VITICULTEUR = 6;
  const CSV_CODE_CHAI_CAVE = 7;
  const CSV_CODE_NEGOCIANT = 8;
  const CSV_CODE_COURTIER = 9;
  const CSV_CODE_APPELLATION = 10;
  const CSV_TYPE_PRODUIT = 11;
  const CSV_MILLESIME = 12;
  const CSV_COTISATION_CVO_NEGOCIANT = 13;
  const CSV_COTISATION_CVO_VITICULTEUR = 14;
  const CSV_VOLUME = 15;
  const CSV_UNITE_VOLUME = 16;
  const CSV_COEF_CONVERSION_VOLUME = 17;
  const CSV_MODE_CONVERSION_VOLUME = 18;
  const CSV_VOLUME_PROPOSE_HL = 19;
  const CSV_VOLUME_ENLEVE_HL = 20;
  const CSV_PRIX_VENTE = 21;
  const CSV_CODE_DEVISE = 22;
  const CSV_UNITE_PRIX_VENTE = 23;
  const CSV_COEF_CONVERSION_PRIX = 24;
  const CSV_MODE_CONVERSION_PRIX = 25;
  const CSV_PRIX_AU_LITRE = 26;
  const CSV_CONTRAT_SOLDE = 27;
  const CSV_DATE_SIGNATURE_OU_CREATION = 28;
  const CSV_DATE_DERNIERE_MODIFICATION = 29;
  const CSV_CODE_SAISIE = 30;
  const CSV_DATE_LIVRAISON = 31;
  const CSV_CODE_MODE_PAIEMENT = 32;
  const CSV_COMPOSTAGE = 33;
  const CSV_TYPE_CONTRAT = 34;
  const CSV_ATTENTE_ORIGINAL = 35;
  const CSV_CATEGORIE_VIN = 36;
  const CSV_CEPAGE = 37;
  const CSV_MILLESIME_ANNEE = 38;
  const CSV_PRIX_HORS_CVO = 39;
  const CSV_PRIX_CVO_INCLUSE = 40;
  const CSV_TAUX_CVO_GLOBAL = 41;
  const CSV_INDICATEUR_CONTRAT_PRIX_VARIABLE = 42;
  const CSV_PRIX_DEFINITIF = 43;
  const CSV_INDICATEUR_PRIX_DEFINITIF = 44;

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
    $i = 1;
    foreach(file($arguments['file']) as $line) {
    	$data = str_getcsv($line, ';');

      try{
        $vrac = $this->importVrac($data);
        $vrac->save();
      } catch (Exception $e) {
        $this->logLigne("ERROR", $e->getMessage(), $data);

        continue;
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

        $v->label = array();

        $date = $this->getDateCampagne($line);
        $v->date_signature = $date->format('Y-m-d');
        $v->date_campagne = $date->format('Y-m-d');
        $v->valide->date_saisie = $this->convertToDateObject($line[self::CSV_DATE_ENREGISTREMENT])->format('Y-m-d');

        $v->vendeur_identifiant = $this->getIdentifiantVendeur($line);
        $v->acheteur_identifiant = $this->getIdentifiantAcheteur($line);
        $v->mandataire_identifiant = null;

        if ($line[self::CSV_CODE_COURTIER]) {
          $v->mandataire_identifiant = $this->getIdentifiantCourtier($line);
        }

        $v->produit = $this->getHash($line[self::CSV_CODE_APPELLATION]);

        $v->millesime = $line[self::CSV_MILLESIME_ANNEE] ? (int)$line[self::CSV_MILLESIME_ANNEE] : null;

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
          	$v->bouteilles_quantite = (int)($this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]) / $v->bouteilles_contenance_volume);
        } elseif(in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_MOUTS,
                                                      VracClient::TYPE_TRANSACTION_VIN_VRAC))) {
          	$v->jus_quantite = $this->convertToFloat($line[self::CSV_VOLUME_PROPOSE_HL]);
        } elseif(in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_RAISINS))) {
      		if($line[self::CSV_UNITE_VOLUME] == 'kg') {  
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
        $v->prix_initial_unitaire_hl = $this->convertToFloat($line[self::CSV_PRIX_AU_LITRE]);
        $v->prix_initial_total = $v->prix_initial_unitaire_hl * $v->volume_propose;

        $v->type_contrat = $this->convertTypeContrat($line[self::CSV_TYPE_CONTRAT]);

        $v->prix_variable = $this->convertOuiNon($line[self::CSV_INDICATEUR_PRIX_DEFINITIF]);

        if(!$v->hasPrixVariable()) {
          $v->prix_unitaire = $v->prix_initial_unitaire;
          $v->prix_unitaire_hl = $v->prix_initial_unitaire_hl;
          $v->prix_total = $v->prix_initial_total;
        }

        if($v->hasPrixVariable() && $line[self::CSV_PRIX_DEFINITIF]) {
          $v->prix_unitaire = $this->convertToFloat($this->calculPrixDefinitifUnitaire($v, $line));
          $v->prix_unitaire_hl = $this->convertToFloat($line[self::CSV_PRIX_DEFINITIF]);
          $v->prix_total = $v->prix_unitaire_hl * $v->volume_propose;
        }

        $v->attente_original = $this->convertOuiNon($line[self::CSV_ATTENTE_ORIGINAL]);

        $v->categorie_vin = $line[self::CSV_CATEGORIE_VIN] == "D" ? VracClient::CATEGORIE_VIN_DOMAINE : VracClient::CATEGORIE_VIN_GENERIQUE;

        $v->cvo_nature = $this->convertCvoNature($line[self::CSV_TYPE_CONTRAT]);

        if(in_array($v->cvo_nature, array(VracClient::CVO_NATURE_VINAIGRERIE, VracClient::CVO_NATURE_NON_FINANCIERE)) || ($this->convertToFloat($line[self::CSV_COTISATION_CVO_VITICULTEUR]) == 0 && $this->convertToFloat($line[self::CSV_COTISATION_CVO_NEGOCIANT]) == 0)) {
          $v->_set('cvo_repartition', VracClient::CVO_REPARTITION_0_VINAIGRERIE);
        } elseif(abs($line[self::CSV_COTISATION_CVO_VITICULTEUR] - $line[self::CSV_COTISATION_CVO_NEGOCIANT]) <= 0.5) {
          $v->_set('cvo_repartition', VracClient::CVO_REPARTITION_50_50);
        } else {
          $v->_set('cvo_repartition', VracClient::CVO_REPARTITION_100_VITI);  
        }

        if(is_null($v->cvo_repartition)) {
          $this->logLigne('WARNING', sprintf("Répartition de la CVO vide"), $line);
        } 

        if($v->cvo_repartition == VracClient::CVO_REPARTITION_100_VITI && $this->convertToFloat($line[self::CSV_COTISATION_CVO_VITICULTEUR]) > 0 && $this->convertToFloat($line[self::CSV_COTISATION_CVO_NEGOCIANT]) > 0) {
          
          $this->logLigne('WARNING', sprintf("Incohérence de CVO VITI v:%s n:%s", $line[self::CSV_COTISATION_CVO_VITICULTEUR], $line[self::CSV_COTISATION_CVO_NEGOCIANT]), $line);
        }

        $v->valide->statut = $line[self::CSV_CONTRAT_SOLDE] == "O" ? VracClient::STATUS_CONTRAT_SOLDE : VracClient::STATUS_CONTRAT_NONSOLDE;

        $v->setInformations();

        return $v;
  }

  protected function getDateCampagne($line) {
	  
	  return $this->convertToDateObject($line[self::CSV_DATE_SIGNATURE_OU_CREATION]);
  }

  protected function getIdentifiantVendeur($line) {

    return sprintf('%s%02d', $line[self::CSV_CODE_VITICULTEUR], $line[self::CSV_CODE_CHAI_CAVE]);
  }

  protected function getIdentifiantAcheteur($line) {

    return sprintf('%s%02d', $line[self::CSV_CODE_NEGOCIANT], 1);
  }

  protected function getIdentifiantCourtier($line) {

    return sprintf('%s%02d', $line[self::CSV_CODE_COURTIER], 1);
  }

  protected function getDensite($line) {
  	if($line[self::CSV_UNITE_PRIX_VENTE] == 'kg' && $line[self::CSV_COEF_CONVERSION_PRIX]) {
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
    if(in_array($vrac->type_transaction, array(VracClient::TYPE_TRANSACTION_RAISINS)) && $line[self::CSV_UNITE_PRIX_VENTE] == 'hl') {

      return $prix_unitaire * $vrac->volume_propose / $vrac->raisin_quantite;
    }

    return $prix_unitaire;
  }

  protected function calculPrixInitialUnitaire($vrac, $line) {

    return $this->calculPrixUnitaire($vrac, $line, $line[self::CSV_PRIX_VENTE]);
  }

  protected function calculPrixDefinitifUnitaire($vrac, $line) {
    
    return $this->calculPrixUnitaire($vrac, $line, $line[self::CSV_PRIX_DEFINITIF]);
  }

  protected function convertTypeTransaction($line) {
    $type_transactions = array(
      self::CSV_TYPE_PRODUIT_INDETERMINE => null,
      self::CSV_TYPE_PRODUIT_RAISINS => VracClient::TYPE_TRANSACTION_RAISINS,
      self::CSV_TYPE_PRODUIT_MOUTS => VracClient::TYPE_TRANSACTION_MOUTS,
      self::CSV_TYPE_PRODUIT_VIN_VRAC => VracClient::TYPE_TRANSACTION_VIN_VRAC,
      self::CSV_TYPE_PRODUIT_TIRE_BOUCHE => VracClient::TYPE_TRANSACTION_VIN_VRAC,
      self::CSV_TYPE_PRODUIT_VIN_LATTES => VracClient::TYPE_TRANSACTION_VIN_VRAC,
      self::CSV_TYPE_PRODUIT_VIN_CRD => VracClient::TYPE_TRANSACTION_VIN_VRAC,
      self::CSV_TYPE_PRODUIT_VIN_BOUTEILLE => VracClient::TYPE_TRANSACTION_VIN_VRAC,
    );

    if(preg_match('/^b[0-9]{1}$/', $line[self::CSV_UNITE_PRIX_VENTE])) {
      if(self::CSV_TYPE_PRODUIT_RAISINS == $line[self::CSV_TYPE_PRODUIT] || self::CSV_TYPE_PRODUIT_MOUTS == $line[self::CSV_TYPE_PRODUIT]) {
        throw new sfException("Le vrac est exprimé en bouteille mais ils s'agit de mout ou de raisins bizarre...");
      }
      return VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE;
    }

    if (array_key_exists($line[self::CSV_TYPE_PRODUIT], $type_transactions)) {

      return $type_transactions[$line[self::CSV_TYPE_PRODUIT]];
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

    return VracClient::TYPE_CONTRAT_SPOT;
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

  protected function getBouteilleContenanceLibelle($v) {
        $contenances = array("0.00375" => '37 cl',
                             "0.005" => '50 cl',
                             "0.0075" => '75 cl',
                             "0.01" => '1 L',
                             "0.015" => '1.5 L',
                             "0.03" => '3 L',
                             "0.05" => '5 L',
                             "0.06" => '6 L');
        $v = $v."";
        if (array_key_exists($v, $contenances)) {
          return $contenances[$v];
        }

        throw new sfException(sprintf('Contenance %s introuvable', $v));
  }

  protected function constructNumeroContrat($line) {

    return $this->convertToDateObject($line[self::CSV_DATE_ENREGISTREMENT])->format('Ymd') . $this->getNumeroArchive($line);
  }

  protected function getNumeroArchive($line) {

    return sprintf("%05d", $line[self::CSV_NUMERO_CONTRAT]);
  }
}