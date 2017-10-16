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
class maintenanceDRMMouvementsRebuildTask extends sfBaseTask {

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
        $this->name = 'drm-mouvements-rebuild';
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
        throw new sfException('/!\ cette tache réécrit tous les mvt. Les statuts de facturation seront perdus même pour les DRM partiellement facturées.');
        $drmId = $arguments['drm'];
        if(!$drmId){
            throw new sfException("L'identifiant d'une drm est necessaire");
        }
        $this->rebuildMouvements($drmId, $options['withdouane']);
    }

    protected function rebuildMouvements($drmId, $withDouane) {
        $drm = DRMClient::getInstance()->find($drmId);
        $drm->clearMouvements();
        $isTeleclare = $drm->isTeledeclare();

        foreach ($drm->getProduits() as $hash => $produit){
            foreach ($produit->getProduitsDetails($isTeleclare) as $detail){
                $detail->storeDroits();
            }
        }

        $drm->generateMouvements();
        if($withDouane) {
		$drm->generateDroitsDouanes();
        }
        $drm->save();
        echo $drm->_id."\n";
    }

}
