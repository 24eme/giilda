<?php

class importConfigurationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'configuration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importConfiguration|INFO] task does things.
Call it with:

  [php symfony importConfiguration|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $import_dir = sfConfig::get('sf_data_dir').'/import/configuration';

    if ($current = acCouchdbManager::getClient()->retrieveDocumentById('CURRENT')) {
        $current->delete();
    }
    
    $current = new Current();
    $current->campagne = '2012-2013';
    $current->save();
    
    $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION', acCouchdbClient::HYDRATE_JSON);
    if ($configuration) {
      acCouchdbManager::getClient()->deleteDoc($configuration);
    }
	    
    $configuration = new Configuration();
    
    $csv = new ProduitCsvFile($configuration, $import_dir.'/produits.csv');
    $configuration = $csv->importProduits();
 
    // $csv = new LabelCsvFile($configuration, $import_dir.'/labels.csv');
    // $configuration = $csv->importLabels();

    
    foreach (file($import_dir.'/details_teledeclaration_drm.csv') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
        if ($detail = $configuration->exist($datas[0])) {
	        $detail = $configuration->get($datas[0])->add('detail')->add($datas[1])->add($datas[2]);
          $detail->readable = (int)$datas[3];
          $detail->writable = (int)$datas[4];
          $detail->details = (int)$datas[5];
          $detail->mouvement_coefficient = (int)$datas[6];
          $detail->vrac = (int)$datas[7];
          $detail->facturable = (int)$datas[8];
          $detail->douane_type = $datas[9];
          $detail->douane_cat = $datas[10];
        }
    }
    
  	foreach (file($import_dir.'/libelle_detail_ligne_teledeclaration_drm.csv') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
        $detail = $configuration->libelle_detail_ligne->get($datas[0])->add($datas[1], $datas[2]);
    }
    
    $contenances = array('75 cl' => 0.0075,
                         '1 L' => 0.01,
                         '1.5 L'=> 0.015,
                         '3 L' => 0.03,
                         'BIB 3 L' => 0.03,
                         '6 L' => 0.06);

    $configurationContenances = $configuration->add('contenances', $contenances);   

    $labels = array('agriculture_biologique' => 'Agriculture Biologique');

    $configurationContenances = $configuration->add('labels', $labels);   



    //    $csv = new LabelCsvFile($configuration, $import_dir.'/labels.csv');

   /*
	 * En attendant de voir avec Tangui pour le probleme CSV et noeud Certif / Millesime
	 */
    /*foreach (file($import_dir.'/details.csv') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
        if ($detail = $configuration->declaration->certifications->exist($datas[0])) {
	        $detail = $configuration->declaration->certifications->get($datas[0])->detail->get($datas[1])->add($datas[2]);
	        $detail->readable = $datas[3];
	        $detail->writable = $datas[4];
        }
    }*/ 
    // Fin
    
  	/*foreach (file($import_dir.'/libelle_detail_ligne.csv') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
        $detail = $configuration->libelle_detail_ligne->get($datas[0])->add($datas[1], $datas[2]);
    }*/

  	$configuration->save();
  }
}