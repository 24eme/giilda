<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maintenanceDRMMouvementsUpdateTask
 *
 * @author mathurin
 */
class maintenanceDRMFavorisRebuildTask extends sfBaseTask {

    protected function configure() {
        $this->addArguments(array(

        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'drm-favoris-rebuild';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceDRMMouvementsUpdate|INFO] task does things.
Call it with:

  [php symfony maintenanceDRMMouvementsUpdate|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $favorisAquitteNodes = array();

        $configuration = ConfigurationClient::getInstance()->getConfiguration();
        foreach ($configuration->mvts_favoris as $key => $value) {
          $favKeys = explode("_",$key);
          if($favKeys[0] == "detailsACQUITTE"){
            if(!array_key_exists($favKeys[1],$favorisAquitteNodes)){
              $favorisAquitteNodes[$favKeys[1]] = array();
            }
            $favorisAquitteNodes[$favKeys[1]][$favKeys[2]] = $value;
          }
        }

        $drms = acCouchdbManager::getClient()->reduce(false)->getView("drm", "all")->rows;
        foreach ($drms as $drmView) {
          echo "Début traitement ".$drmView->id."\n";
          $drm = DRMClient::getInstance()->find($drmView->id);
          if(!$drm->exist('favoris') || !$drm->get('favoris')){
            continue;
          }
          if($drm->favoris->exist('entrees')){
            $entreesFav = $drm->favoris->entrees;
            foreach ($entreesFav as $key => $entree) {
              $drm->favoris->getOrAdd('details')->getOrAdd('entrees')->add($key,$entree);
            }
            $drm->favoris->remove('entrees');
          }
          if($drm->favoris->exist('sorties')){
            $sortiesFav = $drm->favoris->sorties;
            foreach ($sortiesFav as $key => $sortie) {
              $drm->favoris->getOrAdd('details')->getOrAdd('sorties')->add($key,$sortie);
            }
            $drm->favoris->remove('sorties');
          }
            $detailsACQUITTE = $drm->favoris->add('detailsACQUITTE');
            foreach ($favorisAquitteNodes as $catKey => $catValues) {
              foreach ($catValues as $key => $value) {
              $detailsACQUITTE->getOrAdd($catKey)->add($key,$value);
              }
            }
            $good = false;
          //  try{
              $drm->save();
              $good = true;
          //  }catch(Exception $e){
              
          //  }
            if($good){
              echo "FIN reconstruction Favoris ".$drmView->id."\n";
            }
        }
    }

}
