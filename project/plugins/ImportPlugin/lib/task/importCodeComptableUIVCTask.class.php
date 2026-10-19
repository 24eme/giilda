<?php

class importCodeComptableUIVCTask extends importAbstractTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'code_comptable';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importEtablissement|INFO] task does things.
Call it with:

  [php symfony importCodeComptableUIVCTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    foreach(file($arguments['file']) as $line) {
        $line++;
        $line = str_replace("\n", "", $line);
        $data = str_getcsv($line, ';');
        $societe = null;
        $etablissement = null;
        if(preg_match("/^[0-9]{10}$/", $data[0])) {
            $etablissement = EtablissementClient::getInstance()->findByCvi($data[0]);
        }
        if(!$etablissement && preg_match("/^FR/", $data[3])) {
            $etablissement = EtablissementClient::getInstance()->findByNoAccise($data[3]);
        }
        if(!$etablissement && strlen($data[1]) > 4) {
            $etablissement = EtablissementClient::getInstance()->retrieveByName($data[1]);
        }
        if($etablissement) {
            $societe = $etablissement->getSociete();
        }
        if(!$societe) {
            echo "société non trouvé : ".$line."\n";
            continue;
        }

        $societe->add('interpros_metas')->add('INTERPRO-UIVC')->remove('code_comptable');
        $societe->add('interpros_metas')->add('INTERPRO-UIVC')->remove('code_comptable_client');
        $societe->add('interpros_metas')->add('INTERPRO-UIVC')->remove('numero_adherant');
        $societe->add('interpros_metas')->add('INTERPRO-UIVC')->add('numero_adherent', $data[0]);
        $societe->add('interpros_metas')->add('INTERPRO-UIVC')->add('code_comptable_client', $data[6]);
        $societe->save();
    }
  }
}
