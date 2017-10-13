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
class maintenanceDRMUpdateRegionMouvementsTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('drm', sfCommandArgument::REQUIRED, 'DRM'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('withdouane', null, sfCommandOption::PARAMETER_OPTIONAL, 'update droit douane', false),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'drm-update-region-mouvements';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [INFO] task does things.
Call it with:

  [php symfony INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $drmId = $arguments['drm'];
        if(!$drmId){
            throw new sfException("L'identifiant d'une drm est necessaire");
        }
        $this->updateMouvements($drmId, $options['withdouane']);
    }

    protected function updateMouvements($drmId, $withDouane) {
        $drm = DRMClient::getInstance()->find($drmId);
        $etab = EtablissementClient::getInstance()->find($drm->identifiant);
        $drm->region = $etab->region;
        foreach($drm->mouvements as $identite => $mvt){
          if ($identite == $drm->identifiant) {
            $region = $drm->region;
          }else{
            $etab = EtablissementClient::getInstance()->find($identite);
            $region = $etab->region;
          }
          foreach($mvt as $k => $v) {
            $v->region = $region;
//            $drm->mouvements[$identite][$k] = $v;
          }
        }
        $drm->save();
        echo $drm->_id."\n";
    }

}
