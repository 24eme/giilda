<?php

class importDSTask extends sfBaseTask
{

  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_CODE_VITICULTEUR = 10;
  const CSV_CODE_CHAI = 11;
  const CSV_NUMERO_DECLARATION = 2;
  const CSV_NUMERO_LIGNE = 3;
  const CSV_CODE_APPELLATION = 4;
  const CSV_VOLUME_LIBRE = 5;
  const CSV_VOLUME_BLOQUE = 6;
  const CSV_DATE_CREATION = 12;
  const CSV_DATE_MODIFICATION = 13;

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
    $this->name             = 'ds';
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

    $numero = null;
    $lines = array();
    foreach(file($arguments['file']) as $line) {
      $data = str_getcsv($line, ';');
      if($numero && $numero != $data[self::CSV_NUMERO_DECLARATION]) {
        $this->importDS($lines)
      }

      $numero = $data[self::CSV_NUMERO_DECLARATION];
      $lines[$i] = $data;

     
    }

  }

  public function importDS($lines) {
   
    
    foreach($lines as $line) {
       try{

          $ds_new = $this->importLigne($data, $ds);
        } catch (Exception $e) {
          $this->log(sprintf("%s (ligne %s) : %s", $e->getMessage(), $i, implode($data, ";")));
        }
        $i++;
    }
  }

  public function importLigne($line, $ds) {

        $type_transaction = $this->convertTypeTransaction($line[self::CSV_TYPE_PRODUIT]);   

        if (!$type_transaction) {
          return;
        }

        $hash = $this->getHash($line);

        if (!isset($hash)) {

          throw new sfException(sprintf("Le produit avec le code %s n'existe pas", $line[self::CSV_CODE_APPELLATION]));
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

        $v->vendeur_identifiant = $line[self::CSV_CODE_VITICULTEUR];
        $v->acheteur_identifiant = $line[self::CSV_CODE_NEGOCIANT];
        $v->mandataire_identifiant = null;

        if ($line[self::CSV_CODE_COURTIER]) {
          $v->mandataire_identifiant = $line[self::CSV_CODE_COURTIER];
        }

        $v->produit = $hash;

        if($line[self::CSV_CIAPL_SUR_LIE] == "O") {
          $v->label->add(null, "LIE");
        }

        $v->millesime = $line[self::CSV_MILLESIME_ANNEE] ? (int)$line[self::CSV_MILLESIME_ANNEE] : null;

        if (!$v->getVendeurObject()) {
          
          throw new sfException(sprintf("L'etablissement %s n'existe pas", $line[self::CSV_CODE_VITICULTEUR]));
        }

        if (!$v->getAcheteurObject()) {
          
          throw new sfException(sprintf("L'etablissement %s n'existe pas", $line[self::CSV_CODE_NEGOCIANT]));
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
  }

  protected function convertToFloat($number) {

    return str_replace(",", ".", $number) * 1;
  }

  private function getHash($line) 
  {
    $produits_hash = $this->getProduitsHash();

    if (!array_key_exists($line[self::CSV_CODE_APPELLATION]*1, $produits_hash)) {
      
      return null;
    }

    return $produits_hash[$line[self::CSV_CODE_APPELLATION]*1];
  }

  protected function getProduitsHash() {
    if (is_null($this->produits_hash)) {
      $this->produits_hash =  ConfigurationClient::getCurrent()->declaration->getProduitsHashByCodeProduit('INTERPRO-inter-loire');
    }

    return $this->produits_hash;
  }
}
