<?php

class importDRMTask extends sfBaseTask
{

  // cieso
  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_NUMERO_SORTIE = 2;
  const CSV_MOIS_SORTIE = 3;
  const CSV_CODE_PARTENAIRE = 4;
  const CSV_CODE_CHAI_CAVE = 5;
  const CSV_CODE_RECETTE_LOCALE = 6;
  const CSV_DATE_CREATION = 7;
  const CSV_DATE_DERNIERE_MODIFICATION = 8;
  const CSV_CODE_APPELLATION = 9;
  const CSV_CODE_SAISIE = 10;
  const CSV_DATE_SORTIE = 11;
  const CSV_DATE_SAISIE_SORTIE = 12;

  // cilso
  const CSV_NUMERO_LIGNE = 12;
  const CSV_CODE_APPELLATION = 13;
  const CSV_TYPE_VIN = 14;
  const CSV_COTISATION_VITICULEUR_VENTE_DIRECTE = 15;
  const CSV_VOLUME_EXPORT = 16;
  const CSV_VOLUME_CONGE = 17;
  const CSV_VOLUME_CRD = 18;
  const CSV_FACTURE_INDICATEUR = 19;
  const CSV_MILLESIME_INDICATEUR = 20;
  const CSV_CODE_PAYS = 21;
  const CSV_CAMPAGNE_FACTURE = 22;
  const CSV_CODE_SITE = 23;
  const CSV_NUMERO_FACTURE = 24;

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
    $this->name             = 'drm';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importVrac|INFO] task does things.
Call it with:

  [php symfony import:drm|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    set_time_limit(0);

    $drms = array();

    foreach(file($arguments['file']) as $line) {
        $data = str_getcsv($line, ';');

        $id = $this->buildId($line);
        if(!array_key_exists($id, $drms)) {
          $drms[$id] = $this->buildDRM($line);
        }
      
        $this->importLigne($drms[$id], $data);
    }
  }

  public function buildDRM($line) {

    return new DRM();
  }

  public function importLigne($drm, $line) {

  }

  protected function buildId($line) {

  }

  protected function convertToFloat($number) {

    return str_replace(",", ".", $number) * 1;
  }

  protected function convertOuiNon($indicateur) {

    return (int) ($indicateur == 'O');
  }

}
