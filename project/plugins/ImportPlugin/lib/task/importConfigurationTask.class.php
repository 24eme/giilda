<?php

class importConfigurationTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('configuration_id', sfCommandArgument::REQUIRED, "ID couchdb du document à importer"),
            new sfCommandArgument('csv_dir', sfCommandArgument::REQUIRED, "Répertoire contenant les fichiers"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'import';
        $this->name = 'configuration';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [importConfiguration|INFO] task does things.
Call it with:

  [php symfony importConfiguration|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $import_dir = $arguments['csv_dir'];
        
        $configuration = ConfigurationClient::getInstance()->find($arguments['configuration_id']);

        if(!$configuration) {
            $configuration = new Configuration();
            $configuration->_id = $arguments['configuration_id'];
        }

        $csv = new ProduitCsvFile($configuration, $import_dir."/produits.csv");   
        $csv->importProduits();

        foreach (file($import_dir . '/details.csv') as $line) {
            if (preg_match('/^#/', $line))
                continue;
            $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
            $detail = $configuration->get($datas[0])->getOrAdd($datas[1])->add($datas[2])->add($datas[3]);
            $detail->readable = (int) $datas[4];
            $detail->writable = (int) $datas[5];
            $detail->revendiquant = (int) $datas[8];
            $detail->details = (int) $datas[6];
            $detail->mouvement_coefficient = (int) $datas[7];
            $detail->vrac = (int) $datas[9];
            $detail->facturable = (int) $datas[10];
            $detail->douane_type = $datas[11];
            $detail->douane_cat = $datas[12];
            $detail->taxable_douane = (int) $datas[13];
        }

        foreach (file($import_dir . '/libelle_detail_ligne.csv') as $line) {
            $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
            $detail = $configuration->libelle_detail_ligne->getOrAdd($datas[0])->getOrAdd($datas[1]);
            $detail->libelle = $datas[2];
            $detail->libelle_long = $datas[3];
            $detail->description = $datas[4];
        }

        foreach (file($import_dir . '/mouvements_favoris.csv') as $mvtLine) {
            $mvt = explode(";", preg_replace('/"/', '', str_replace("\n", "", $mvtLine)));
            $configuration->getOrAdd('mvts_favoris')->add($mvt[0], $mvt[0]);
        }

        $configuration->save();
    }

}
