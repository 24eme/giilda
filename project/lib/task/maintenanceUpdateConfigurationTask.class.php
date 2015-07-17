<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maintenanceUpdateConfigurationTask
 *
 * @author mathurin
 */
class maintenanceUpdateConfigurationTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'update-configuration';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION', acCouchdbClient::HYDRATE_JSON);

        $import_dir = sfConfig::get('sf_data_dir') . '/import/configuration';
        
        if (!$configuration) {
            new sfException("La configuration n'existe pas");
        }

        unset($configuration->libelle_detail_ligne);        
        unset($configuration->declaration->details);
        unset($configuration->declaration->detail);
        ConfigurationClient::getInstance()->storeDoc($configuration);
        
        $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION');

        foreach (file($import_dir . '/details_teledeclaration_drm.csv') as $line) {
            if (preg_match ('/^#/', $line)) continue;
            $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));

            $detail = $configuration->get($datas[0])->get($datas[1])->add($datas[2])->add('detail')->add($datas[3])->add($datas[4]);
            $detail->readable = (int) $datas[5];
            $detail->writable = (int) $datas[6];
            $detail->details = (int) $datas[7];
            $detail->mouvement_coefficient = (int) $datas[8];
            $detail->vrac = (int) $datas[9];
            $detail->facturable = (int) $datas[10];
            $detail->douane_type = $datas[11];
            $detail->douane_cat = $datas[12];

        }

        foreach (file($import_dir . '/libelle_detail_ligne_teledeclaration_drm.csv') as $line) {
            $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
            $detail = $configuration->libelle_detail_ligne->getOrAdd($datas[0])->get($datas[1])->add($datas[2]);
            $detail->libelle =  $datas[3];
            $detail->libelle_long =  $datas[4];
            $detail->description =  $datas[5];
        }

        $csv = new ProduitCsvFile($configuration, $import_dir . '/produits_teledeclaration_drm.csv');
        $configuration = $csv->importProduits();

        $configuration->save();
    }

}
