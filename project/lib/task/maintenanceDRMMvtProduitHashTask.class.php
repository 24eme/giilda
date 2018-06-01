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
class maintenanceDRMMvtProduitHashTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('drm', sfCommandArgument::REQUIRED, 'DRM'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'drm-mvt-produit-hash';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
[maintenanceDRMMvtProduitHashTask|INFO] réécrit la hash, ou s'assure que la hash est correcte dans les mouvements
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

        $drm = DRMClient::getInstance()->find($drmId,acCouchdbClient::HYDRATE_JSON);

        if(!$drm){
            throw new sfException("La DRM $drmId n'existe pas en base");
        }

        foreach ($drm->mouvements as $identifiantIL => $mvtsList) {
            foreach ($mvtsList as $keyUniq => $mvt) {
                if(preg_match('/details(ACQUITTE)?\/([a-zA-Z0-9]+)/',$mvt->produit_hash)){
                    echo $drm->_id.";CORRECTE;mouvements/".$identifiantIL."/".$keyUniq.";".$mvt->produit_hash."\n";
                }else{
                    $newHash = $mvt->produit_hash."/details/DEFAUT";
                    $mvt->produit_hash = $newHash;
                    echo $drm->_id.";CHANGED;mouvements/".$identifiantIL."/".$keyUniq.";".$mvt->produit_hash.";".$newHash."\n";
                }
            }
        }
        acCouchdbManager::getClient()->storeDoc($drm);
    }


}
