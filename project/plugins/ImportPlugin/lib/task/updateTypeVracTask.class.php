<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class updateTypeVracTask
 * @author mathurin
 */
class updateTypeVracTask extends importAbstractTask
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


  protected $error_term = "\033[31mERREUR:\033[0m";
  protected $warning_term = "\033[33m----->ATTENTION:\033[0m ";

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

    $this->namespace        = 'update';
    $this->name             = 'type-vrac';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateTypeVrac|INFO] task does things.
Call it with:

  [php symfony update:type-vrac|INFO]
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
        $vrac = $this->updateVrac($data);
	 if($vrac !== null){
                echo " Save du vrac ".$vrac->_id." \n";
	        $vrac->save();
	 }
      } catch (Exception $e) {
        $this->logLigne($this->error_term, $e->getMessage(), $data);

        continue;
      }

      $i++;
    }

  }

  public function updateVrac($line) {

        $num_contrat = $this->constructNumeroContrat($line);
        $v = VracClient::getInstance()->findByNumContrat($num_contrat);
        
        if (!$v) {
            echo $this->error_term." -> Le contrat ".$num_contrat." n'existe pas en base, cela est curieux! \n";
	return null;
        }else{
            echo " Traitement du contrat numéro ".$num_contrat." \n";
            if(($line[self::CSV_TYPE_PRODUIT] == self::CSV_TYPE_PRODUIT_TIRE_BOUCHE) || 
                    ($line[self::CSV_TYPE_PRODUIT] == self::CSV_TYPE_PRODUIT_VIN_LATTES) || 
                    ($line[self::CSV_TYPE_PRODUIT] == self::CSV_TYPE_PRODUIT_VIN_CRD)) {
                        $v->type_transaction = VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE;
            $v->bouteilles_contenance_volume = 0.0075;
            $v->bouteilles_contenance_libelle = "75 cl";

            echo $this->warning_term." ";
            if($v->prix_unitaire > 20){
            $old_prix = $v->prix_unitaire;
              $v->prix_unitaire *= $v->bouteilles_contenance_volume;
              $v->prix_unitaire = $this->convertToFloat($v->prix_unitaire);
              echo " le prix unitaire a changé : ".$old_prix." => ".$v->prix_unitaire."  ";
            }

              $v->prix_initial_unitaire = $this->convertToFloat($v->prix_unitaire);
              $v->bouteilles_quantite = (int) ($v->volume_propose / $v->bouteilles_contenance_volume);              
              $v->prix_total = $this->convertToFloat($v->bouteilles_quantite *  $v->prix_unitaire);
              
              $v->prix_initial_unitaire_hl = $this->convertToFloat($v->prix_total / $v->volume_propose);
              $v->prix_unitaire_hl = $this->convertToFloat($v->prix_initial_unitaire_hl);
              $v->prix_initial_total = $this->convertToFloat($v->prix_total);
              
              echo " ===> contrat ".$num_contrat."\n";
              return $v;
            }	
        return null;
        }
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

  protected function constructNumeroContrat($line) {

    return $this->convertToDateObject($line[self::CSV_DATE_ENREGISTREMENT])->format('Ymd') . $this->getNumeroArchive($line);
  }

  protected function getNumeroArchive($line) {

    return sprintf("%05d", $line[self::CSV_NUMERO_CONTRAT]);
  }
}
