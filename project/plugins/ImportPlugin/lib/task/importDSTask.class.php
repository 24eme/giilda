<?php

class importDSTask extends importAbstractTask
{

  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_CODE_VITICULTEUR = 11;
  const CSV_CODE_CHAI = 12;
  const CSV_NUMERO_DECLARATION = 2;
  const CSV_NUMERO_LIGNE = 3;
  const CSV_CODE_APPELLATION = 4;
  const CSV_VOLUME_LIBRE = 5;
  const CSV_VOLUME_BLOQUE = 6;
  const CSV_DATE_CREATION = 13;
  const CSV_DATE_MODIFICATION = 14;

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
        try{
          $this->importDS($lines);
        } catch (Exception $e) {
          $this->log(sprintf("%s (ligne %s) : %s", $e->getMessage(), $i, implode($data, ";")));
        }
        $lines = array();
      }
      
      $numero = $data[self::CSV_NUMERO_DECLARATION];
      $lines[$i] = $data;
      $i++;
    }

  }

  public function importDS($lines) {
    $ds = null;

    foreach($lines as $i => $line) {
        $ds = $this->importLigne($ds, $line);
    }

    $ds->updateStatut();
    $ds->save();
  }

  public function importLigne($ds, $line) {
    if (is_null($ds)) {
      $ds = DSClient::getInstance()->createOrFind($this->getIdentifiant($line), $this->getDateCreation($line));

      if(!$ds->getEtablissementObject()) {
        throw new sfException(sprintf("L'etablissement %s n'existe pas", $this->getIdentifiant($line)));
      }

      $ds->numero_archive = $this->getNumeroArchive($line);
    }

    $config_produit = $this->getConfigurationHash($line[self::CSV_CODE_APPELLATION]);

    $produit = $ds->declarations->add($config_produit->getHashForKey());
    $produit->produit_hash = $config_produit->getHash();
    $produit->produit_libelle = $config_produit->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
    $produit->stock_declare = $this->convertToFloat($line[self::CSV_VOLUME_LIBRE]);
    $produit->vci = $this->convertToFloat($line[self::CSV_VOLUME_BLOQUE]);

    return $ds;
  }

  protected function getDateCreation($line) {

  	return $this->convertToDateObject($line[self::CSV_DATE_CREATION])->format('Y').'-07-31';
  }

  protected function getIdentifiant($line) {

    return sprintf('%s%02d', $line[self::CSV_CODE_VITICULTEUR], $line[self::CSV_CODE_CHAI]);
  }

  protected function getNumeroArchive($line) {

    return sprintf("%05d", $line[self::CSV_NUMERO_DECLARATION]);
  }
}
