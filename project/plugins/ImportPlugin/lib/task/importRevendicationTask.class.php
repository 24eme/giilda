<?php

class importRevendicationTask extends importAbstractTask
{
  const CSV_LIGNE_REGION = 0;
  const CSV_LIGNE_CAMPAGNE = 1;
  const CSV_LIGNE_ETABLISSEMENT = 2;
  const CSV_LIGNE_CODE_APPELLATION = 3;
  const CSV_LIGNE_INUTILE = 4;
  const CSV_MOUVEMENT_DOSSIER = 5;
  const CSV_MOUVEMENT_CAMPAGNE = 6;
  const CSV_MOUVEMENT_CODE_PARTENAIRE = 7;
  const CSV_MOUVEMENT_CODE_CHAI = 8;
  const CSV_MOUVEMENT_CODE_APPELLATION = 9;
  const CSV_MOUVEMENT_CODE_MOUVEMENT = 10;
  const CSV_MOUVEMENT_DATE_MOUVEMENT = 11;
  const CSV_MOUVEMENT_DATE_HEURE_SAISIE = 12;
  const CSV_MOUVEMENT_STOCK_FIN_CAMPAGNE = 13;
  const CSV_MOUVEMENT_VOLUME_CONTRAT = 14;
  const CSV_MOUVEMENT_VOLUME_SORTIE = 15;
  const CSV_MOUVEMENT_VOLUME_ENLEVE = 16;
  const CSV_MOUVEMENT_VOLUME_CONTRAT_NOUVELLE_RECOLTE = 17;
  const CSV_MOUVEMENT_VOLUME_AGREE_COMMERCIALISABLE = 18;
  const CSV_MOUVEMENT_VOLUME_AGREE_BLOQUE = 19;
  const CSV_MOUVEMENT_VOLUME_SUSCEPTIBLE_RECLASSEMENT = 20;
  const CSV_MOUVEMENT_VOLUME_REGULARISATION = 21;
  const CSV_MOUVEMENT_VOLUME_AGR2 = 22;
  const CSV_MOUVEMENT_VOLUME_VOLUME_BLOQUE_CAMPAGNE_PRECEDENTE = 23;
  const CSV_MOUVEMENT_STOCK_COURANT = 24;
  const CSV_MOUVEMENT_TYPE_DOCUMENT = 25;
  const CSV_MOUVEMENT_NUMERO_DOCUMENT = 26;
  const CSV_MOUVEMENT_COMMENTAIRE = 27;
  const CSV_MOUVEMENT_VOLUME_RECOLTE = 28;
  const CSV_MOUVEMENT_SUPERFICIE_RECOLTE = 29;

  protected function configure()
  {
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

    $this->namespace        = 'import';
    $this->name             = 'revendication';
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
    $id = null;
    $lines = array();
    foreach(file($arguments['file']) as $line) {
      $data = str_getcsv($line, ';');
      if($id && $id != $this->getId($data)) {
        $this->importRevendication($lines);
        $lines = array();
      }
      
      $id = $this->getId($data);
      $lines[$i] = $data;
      $i++;
    }

    if(count($lines) > 0) {
      $this->importRevendication($lines);
    }
  }

  public function importRevendication($lines) {
    $rev = null;
    $etablissement_id = null;

    foreach($lines as $i => $line) {
      try{
        $rev = $this->importLigne($rev, $line);
      } catch (Exception $e) {
        $this->logLigne("ERROR", $e->getMessage(), $line, $i);
        return;
      }
    }

    try{
      $rev->save();
    } catch (Exception $e) {
      $this->logLignes("ERROR", $e->getMessage(), $lines, $i);
    }
  }

  public function importLigne($rev, $line) {
    if (is_null($rev)) {
      $rev = RevendicationClient::getInstance()->createOrFind($this->getODG($line), $this->getCampagne($line));

      if (!$rev->isNew()) {
        throw new sfException(sprintf("La revendication existe déjà %s-%s", $this->getODG($line), $this->getCampagne($line)));
      }
    }

    $etablissement = EtablissementClient::getInstance()->find($this->getIdentifiant($line), acCouchdbClient::HYDRATE_JSON);

    if(!$etablissement) {
      throw new sfException(sprintf("L'etablissement %s n'existe pas", $this->getIdentifiant($line)));
    }

    $rev->addVolumeSaisi($etablissement->identifiant, 
                         $this->getHash($this->getCodeProduit($line)), 
                         $this->convertToFloat($line[self::CSV_MOUVEMENT_VOLUME_AGREE_COMMERCIALISABLE]), 
                         $this->convertToDateObject($line[self::CSV_MOUVEMENT_DATE_MOUVEMENT])->format('Y-m-d'));

    return $rev;
  }

  protected function getIdentifiant($line) {

    return $line[self::CSV_LIGNE_ETABLISSEMENT];
  }

  protected function getODG($line) {

    $regions = array('T' => EtablissementClient::REGION_CVO,
                     'N' => EtablissementClient::REGION_CVO,
                     'A' => EtablissementClient::REGION_CVO);

    if(!array_key_exists($line[self::CSV_LIGNE_REGION], $regions)) {

      throw new sfException(sprintf("La région %s n'existe pas", $line[self::CSV_LIGNE_REGION]));
    }

    return $regions[$line[self::CSV_LIGNE_REGION]];
  }

  protected function getCampagne($line) {

    return $line[self::CSV_LIGNE_CAMPAGNE];
  }

  protected function getId($line) {
    
    return RevendicationClient::getInstance()->getId($this->getODG($line), $this->getCampagne($line));
  }

  protected function getCodeProduit($line) {
    
    return $line[self::CSV_LIGNE_CODE_APPELLATION];
  }
}
