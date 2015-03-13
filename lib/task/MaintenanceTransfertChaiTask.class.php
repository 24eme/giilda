<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MaintenanceTransfertChaiTask
 *
 * @author mathurin
 */
class MaintenanceTransfertChaiTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('campagne', sfCommandArgument::REQUIRED, "Campagne"),
            new sfCommandArgument('chai-src', sfCommandArgument::REQUIRED, "Chai Source"),
            new sfCommandArgument('chai-dst', sfCommandArgument::REQUIRED, "Chai Destination"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'contratTransfertChai';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [contratTransfertChai|INFO] task update contrat from chai src to chai dst.
Call it with:

  [php symfony maintenance:contratTransfertChai|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if (!isset($arguments['campagne']) || !isset($arguments['chai-src']) || (!$arguments['chai-dst'])) {
            throw new sfException("la campagne le chai source et le chai destination sont obligatoires");
        }

        $campagne = $arguments['campagne'];
        $chaiSrc = $arguments['chai-src'];
        $chaiDst = $arguments['chai-dst'];

        echo "### Début du maj contrat sur la campagne [$campagne] => passage du chai $chaiSrc au chai $chaiDst \n";
        $contrats = $this->getContratsCampagneEtb($campagne, $chaiSrc);
        $this->majContratAndSetChaiDst($contrats, $chaiSrc, $chaiDst);
    }

    protected function getContratsCampagneEtb($campagne, $chaiSrc) {
        $societeId = substr($chaiSrc, 0, 6);
        $societe = SocieteClient::getInstance()->findByIdentifiantSociete($societeId);
        if (!$societe) {
            throw new sfException("la societe d'id $societeId n'a pas été trouvée");
        }
        return VracClient::getInstance()->retrieveByCampagneEtablissementAndStatut($societe, $campagne);
    }

    protected function majContratAndSetChaiDst($contrats, $chaiSrc, $chaiDst) {
        if (!count($contrats)) {
            echo "Aucun contrats pour cette société\n";
        }

        foreach ($contrats as $contratView) {
            $vrac = VracClient::getInstance()->find($contratView->id);
            if ($vrac->vendeur_identifiant == $chaiSrc) {
                $vrac->vendeur_identifiant = $chaiDst;
                $vrac->setVendeurInformations();
                $vrac->save();
                echo "Contrat " . $vrac->numero_contrat . " [ chai " . $chaiSrc . " ] => chai " . $chaiDst . "\n";
                continue;
            }
            if ($vrac->acheteur_identifiant == $chaiSrc) {
                $vrac->acheteur_identifiant = $chaiDst;
                $vrac->setAcheteurInformations();
                $vrac->save();
                echo "Contrat " . $vrac->numero_contrat . " [ chai " . $chaiSrc . " ] => chai " . $chaiDst . "\n";
                continue;
            }
        }
    }

}
