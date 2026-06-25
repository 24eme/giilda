<?php

class importFichierTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
          new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, 'Identifiant'),
          new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Fichier à importer'),
      	));
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
          new sfCommandOption('libelle', null, sfCommandOption::PARAMETER_OPTIONAL, 'Libellé'),
          new sfCommandOption('date_depot', null, sfCommandOption::PARAMETER_OPTIONAL, 'Date dépôt'),
        ));
        $this->namespace        = 'import';
        $this->name             = 'fichier';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $identifiant = $arguments['identifiant'];
        $file = $arguments['file'];
        $libelle = $options['libelle'];
        $dateDepot = $options['date_depot'];

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateDepot)) {
            $dateDepot = date('Y-m-d');
        }
        if (!is_file($file)) {
          echo "ERREUR;not a file;$file\n";
          exit;
        }
        $infos = pathinfo($file);
        if (!isset($infos['extension']) || !isset($infos['filename'])) {
            echo "ERREUR;invalid filename;$file\n";
            exit;
        }
        if (!$libelle) {
            $libelle = $infos['filename'];
        }
        $fichier = FichierClient::getInstance()->createDoc($identifiant, true);
        $fichier->date_depot = $dateDepot;
        $fichier->libelle = $libelle;
        $fichier->save();
        try {
            $fichier->storeFichier($file);
        } catch (Exception $e) {
            echo $e."\n";
            $fichier->delete();
            exit;
        }
        $fichier->save();
        echo "SUCCESS;$identifiant;$fichier->_id\n";
    }
}
