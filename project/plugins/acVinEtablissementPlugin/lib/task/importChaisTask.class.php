<?php

class importChaisTask extends sfBaseTask
{

  const CSV_ID_EXTRAVITIS = 0;
  const CSV_TYPE_DE_CHAI = 1;
  const CSV_CHAI_DE_VINIFICATION = 2;
  const CSV_ELEVAGE_ET_VIEILLISSEMENT = 3;
  const CSV_CENTRE_DE_CONDITIONNEMENT = 4;
  const CSV_LIEU_DE_STOCKAGE = 5;
  const CSV_PRESTATAIRE_DE_SERVICE = 6;
  const CSV_INTITULE = 7;
  const CSV_TYPE = 8;
  const CSV_ADRESSE = 9;
  const CSV_ADRESSE_2 = 10;
  const CSV_ADRESSE_3 = 11;
  const CSV_COMMUNE = 12;
  const CSV_CODE_POSTAL = 13;

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'import';
        $this->name = 'Chais';
        $this->briefDescription = 'Import des chais (via le csv issu de scrapping)';
        $this->detailedDescription = <<<EOF
EOF;

        $this->convert2attributs = array();
        $this->convert2attributs['Chai de vinification'] = EtablissementClient::CHAI_ATTRIBUT_VINIFICATION;
        $this->convert2attributs['Elevage et vieillissement'] = EtablissementClient::CHAI_ATTRIBUT_ELEVAGE;
        $this->convert2attributs['Centre de conditionnement'] = EtablissementClient::CHAI_ATTRIBUT_CONDITIONNEMENT;
        $this->convert2attributs['Lieu de stockage'] = EtablissementClient::CHAI_ATTRIBUT_STOCKAGE;
        $this->convert2attributs['Prestataire de service'] = EtablissementClient::CHAI_ATTRIBUT_PRESTATAIRE;


    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $chais = array();
        $last_id = null;
        foreach(file($arguments['file']) as $line) {
            $line = str_replace("\n", "", $line);
            if(preg_match("/^000000#/", $line)) {
                continue;
            }
            $data = str_getcsv($line, ';');
            if (count($chais) && $last_id != $data[0]) {
              $this->saveChais($chais);
              $chais = array();
            }
            $last_id = $data[0];
            $chais[] = $data;
        }
        $this->saveChais($chais);
    }

    protected function saveChais($chais) {
      $id = sprintf('ETABLISSEMENT-%06d01', $chais[0][self::CSV_ID_EXTRAVITIS]);
      $etablissement = EtablissementClient::getInstance()->find($id);
      if (!$etablissement) {
        foreach ($chais as $i => $c) {
          if ($c[self::CSV_TYPE] == 'Négoce') {
            $id = sprintf('SOCIETE-%06d', $chais[0][self::CSV_ID_EXTRAVITIS]);
            $societe = SocieteClient::getInstance()->find($id);
            if (!$societe) {
              echo "ERROR: pas de société trouvée pour $id\n";
              return;
            }
            $etablissement = $societe->createEtablissement(EtablissementFamilles::FAMILLE_NEGOCIANT);
            $etablissement->nom = $societe->raison_sociale;
          }
        }
      }
      if (!$etablissement) {
        echo "ERROR: pas d'établissement trouvé pour $id\n";
        return;
      }
      $etablissement->remove('chais');
      foreach ($chais as $i => $c) {
        $mychai = $etablissement->add('chais')->add($i);
        $mychai->adresse = $c[self::CSV_ADRESSE];
        $mychai->nom = ($c[self::CSV_INTITULE])? $c[self::CSV_INTITULE] : $c[self::CSV_COMMUNE];
        if ($c[self::CSV_ADRESSE_2]) {
          $mychai->adresse .= ' - '.$c[self::CSV_ADRESSE_2];
        }
        if ($c[self::CSV_ADRESSE_3]) {
          $mychai->adresse .= ' - '.$c[self::CSV_ADRESSE_3];
        }
        $mychai->commune = $c[self::CSV_COMMUNE];
        $mychai->code_postal = $c[self::CSV_CODE_POSTAL];
        $attributs = $mychai->add('attributs');
        if ($c[self::CSV_CHAI_DE_VINIFICATION]) {
          $attributs->add($this->convert2attributs[$c[self::CSV_CHAI_DE_VINIFICATION]], $c[self::CSV_CHAI_DE_VINIFICATION]);
        }
        if ($c[self::CSV_ELEVAGE_ET_VIEILLISSEMENT]) {
          $attributs->add($this->convert2attributs[$c[self::CSV_ELEVAGE_ET_VIEILLISSEMENT]], $c[self::CSV_ELEVAGE_ET_VIEILLISSEMENT]);
        }
        if ($c[self::CSV_CENTRE_DE_CONDITIONNEMENT]) {
          $attributs->add($this->convert2attributs[$c[self::CSV_CENTRE_DE_CONDITIONNEMENT]], $c[self::CSV_CENTRE_DE_CONDITIONNEMENT]);
        }
        if ($c[self::CSV_LIEU_DE_STOCKAGE]) {
          $attributs->add($this->convert2attributs[$c[self::CSV_LIEU_DE_STOCKAGE]], $c[self::CSV_LIEU_DE_STOCKAGE]);
        }
        if ($c[self::CSV_PRESTATAIRE_DE_SERVICE]) {
          $attributs->add($this->convert2attributs[$c[self::CSV_PRESTATAIRE_DE_SERVICE]], $c[self::CSV_PRESTATAIRE_DE_SERVICE]);
        }
        $mychai->partage = ($c[self::CSV_TYPE_DE_CHAI] == 'CHAI Partagé');
      }
      $etablissement->save();
      echo $etablissement->_id."\n";
    }
}
