<?php

abstract class importDSTask extends importAbstractTask
{

  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_NUMERO_DECLARATION = 2;
  const CSV_CODE_VITICULTEUR = 3;
  const CSV_CODE_CHAI = 4;
  const CSV_DATE_CREATION = 5;
  const CSV_DATE_MODIFICATION = 6;
  const CSV_DATE_DECLARATION = 8;
  const CSV_NUMERO_LIGNE = 12;
  const CSV_CODE_APPELLATION = 13;
  const CSV_VOLUME_1 = 14;
  const CSV_VOLUME_2 = 15;

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
        $this->importDS($lines);
        $lines = array();
      }
      
      $numero = $data[self::CSV_NUMERO_DECLARATION];
      $lines[$i] = $data;
      $i++;
    }

    if(count($lines) > 0) {
      $this->importDS($lines);
    }

  }

  public function importDS($lines) {
    $ds = null;

    foreach($lines as $i => $line) {
      try{
        $ds = $this->importLigne($ds, $line);
      } catch (Exception $e) {
        $this->logLigne("ERROR", $e->getMessage(), $line, $i);
        return;
      }
    }

    try{
      $ds->updateStatut();
      $ds->save();
    } catch (Exception $e) {
        $this->logLignes("ERROR", $e->getMessage(), $lines, $i);
    }
  }

  public function importLigne($ds, $line) {
    if (is_null($ds)) {
      $ds = DSClient::getInstance()->findOrCreateDsByEtbId($this->getIdentifiant($line), $this->getDateCreation($line));
      $ds->date_emission = $ds->date_stock;

      if (!$ds->isNew()) {
        throw new sfException(sprintf("La DS existe déjà %s-%s", $ds->identifiant, $ds->periode));
      }

      if(!$ds->getEtablissementObject()) {
        throw new sfException(sprintf("L'etablissement %s n'existe pas", $this->getIdentifiant($line)));
      }

      $ds->numero_archive = $this->getNumeroArchive($line);
    }

    return $ds;
  }

  protected function hasNumeroLigne($line) {
    
    return isset($line[self::CSV_NUMERO_LIGNE]);
  }

  abstract protected function getDateCreation($line);

  protected function getDateCreationJuillet($line) {
    $annee = $line[self::CSV_CAMPAGNE] * 1 + 1;

    return $annee.'-07-31';
  }

  protected function getDateCreationFevrier($line) {
    $annee = $line[self::CSV_CAMPAGNE] * 1 + 1;
    $day = (date('L', strtotime($annee.'-01-01'))) ? '29' : '28';
    return $annee.'-02-'.$day;
  }

  protected function getIdentifiant($line) {
    $code_chai = $line[self::CSV_CODE_CHAI];

    if($code_chai == "0.0") {
      $code_chai = 1;
    }

    return sprintf('%s%02d', $line[self::CSV_CODE_VITICULTEUR], $code_chai);
  }

  protected function getNumeroArchive($line) {

    return sprintf("%05d", $line[self::CSV_NUMERO_DECLARATION]);
  }
}
