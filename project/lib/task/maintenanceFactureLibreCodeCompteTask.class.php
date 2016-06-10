<?php

class maintenanceFactureLibreCodeCompteTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array());

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('directory', null, sfCommandOption::PARAMETER_REQUIRED, 'Output directory', '.'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'facture-libre-code-compte';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceFactureLibreCodeCompteTask|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        echo "TRAITEMENT DU DOC DE COMPTA\n";
        $comptaDoc = ComptabiliteClient::getInstance()->findCompta();
        $correspondanceKeys = array();
        $KeyNodeToRemove = array();
        foreach ($comptaDoc->getIdentifiantsAnalytiques() as $oldKey => $identifiant_analytique) {
          $newKey = $identifiant_analytique->identifiant_analytique_numero_compte.'_'.$identifiant_analytique->identifiant_analytique;
          if($newKey!=$oldKey){
            $comptaDoc->getOrAdd('identifiants_analytiques')->add($newKey,$identifiant_analytique);
            $KeyNodeToRemove[] = $oldKey;
          }
          $correspondanceKeys[$oldKey] = $newKey;
        }
        foreach ($KeyNodeToRemove as $oldKey) {
          $comptaDoc->getOrAdd('identifiants_analytiques')->remove($oldKey);
        }
          $comptaDoc->save();

            // echo "TRAITEMENT DU DOC DE FACTURE LIBRE\n";
            // $mouvementsfactureLibreDocs = MouvementsFactureClient::getInstance()->startkey('MOUVEMENTSFACTURE-0000000000')->endkey('MOUVEMENTSFACTURE-9999999999')->execute();
            //
            //
            // foreach ($mouvementsfactureLibreDocs as $mouvementfacture) {
            //   foreach ($mouvementfacture->mouvements as $socId => $mvtSoc) {
            //     foreach ($mvtSoc as $uniqId => $mvt) {
            //       echo "TRANSFORME ".$mvt->identifiant_analytique." => ".$correspondanceKeys[$mvt->identifiant_analytique]." \n";
            //       $mvt->identifiant_analytique = $correspondanceKeys[$mvt->identifiant_analytique];
            //     }
            //   }
            //   $mouvementfacture->save();
            // }
        $factures = FactureEtablissementView::getInstance()->getFactureNonVerseeEnCompta();
        foreach ($factures as $factureObj) {
            $facture = FactureClient::getInstance()->find($factureObj->id);
            if ($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS)) {
                echo "\nModification des ligne de la facture " . $factureObj->id;
                foreach ($facture->lignes as $lignes) {
                    $lignes->produit_identifiant_analytique = (isset($correspondanceKeys[$lignes->produit_identifiant_analytique]))? $correspondanceKeys[$lignes->produit_identifiant_analytique] : $lignes->produit_identifiant_analytique;
                    echo " (" . $lignes->produit_identifiant_analytique . ")";
                    if (!preg_match('/^([0-9]+)_([0-9]+)$/', $lignes->produit_identifiant_analytique)) {
                        throw new sfException(sprintf("L'identifiant analytique (composé) %s n'a pas le bon format!", $lignes->produit_identifiant_analytique));
                    }
                    foreach ($lignes->details as $detail) {
                        $identifiants_compte_analytique = explode('_', $lignes->produit_identifiant_analytique);
                        $detail->add('identifiant_analytique', $identifiants_compte_analytique[1]);
                        $detail->add('code_compte', $identifiants_compte_analytique[0]);
                    }
                }
                echo "\n";
            }
            $facture->save();
        }
    }

}
