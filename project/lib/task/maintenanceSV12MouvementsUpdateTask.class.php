<?php

class maintenanceSV12MouvementsUpdateTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('sv12', sfCommandArgument::REQUIRED, 'SV12'),
            new sfCommandArgument('repartition', sfCommandArgument::OPTIONAL, 'Repartition')
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'sv12-mouvements-update';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceSV12MouvementsUpdate|INFO] task does things.
Call it with:

  [php symfony maintenanceSV12MouvementsUpdate|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $sv12 = $arguments['sv12'];



        if ($sv12 == 'all') {
            $this->updateAllSV12();
        } else {
            $this->updateRepartitionOneSV12($sv12, $arguments);
        }
    }

    protected function updateRepartitionOneSV12($sv12, $arguments) {
        $repartition = $arguments['repartition'];

        if (!$repartition) {
            throw new sfException("l'argument répartition doit être renseigné");
        }
        $sv12AndModifs = array();

        $sv12Obj = SV12Client::getInstance()->find($sv12);
        if (!$sv12Obj) {
            throw new sfException("la sv12 n'a pas été trouvée");
        }
        $sv12AndModifs[] = $sv12Obj;
        $modif = 1;
        while ($sv12Modif = SV12Client::getInstance()->find($sv12 . '-' . sprintf('M%02d', $modif))) {
            $sv12AndModifs[] = $sv12Modif;
            $modif++;
        }
        $configuration = ConfigurationClient::getConfigurationByCampagne($sv12Obj->campagne);
        foreach ($sv12AndModifs as $sv12ToModify) {
            foreach ($sv12ToModify->mouvements as $identifiant => $mvts) {
                foreach ($mvts as $mvtKey => $mvt) {
                    $produit = $configuration->get($mvt->produit_hash);
                    $cvo = $produit->getDroitByType($mvt->date, 'INTERPRO-inter-loire', 'cvo')->taux;
                    if ($identifiant == $sv12ToModify->identifiant) {
                        
                             $mvt->cvo = $cvo / 2;
                             $mvt->facture = 0;
                            $mvt->volume = $mvt->volume*-1;
                  
                        } else {
                        $mvt->cvo = $cvo / 2;
                        $mvt->facturable = 1;
                    }
                }
            }
           $sv12ToModify->save();
        }
    }

    protected function updateAllSV12() {
        $rows = SV12AllView::getInstance()->findAll();

        foreach ($rows as $row) {
            try {
                echo $row->id . "\n";
                $sv12 = SV12Client::getInstance()->find($row->id);
                foreach ($sv12->mouvements as $etablissement_id => $mouvements) {
                    foreach ($mouvements as $mouvement) {
                        $mouvement->date = SV12Client::getInstance()->buildDate($sv12->periode);

                        if (!$mouvement->detail_identifiant) {

                            continue;
                        }

                        try {
                            $vrac = VracClient::getInstance()->find($mouvement->detail_identifiant);
                            if (!$vrac) {
                                throw new sfException(sprintf("trouve pas le contrat %s", $mouvement->detail_identifiant));
                            }
                        } catch (Exception $e) {
                            throw new sfException(sprintf("trouve pas le contrat %s", $mouvement->detail_identifiant));
                        }

                        $mouvement->detail_libelle = $vrac->numero_archive;
                        $sv12_contrat = $sv12->contrats->{$mouvement->vrac_numero};
                        if ($sv12_contrat->vendeur_identifiant == $etablissement_id) {
                            $mouvement->vrac_destinataire = $sv12->declarant->nom;
                            $mouvement->region = $vrac->vendeur->region;
                        }

                        if ($sv12->identifiant == $etablissement_id) {
                            $mouvement->vrac_destinataire = $sv12_contrat->vendeur_nom;
                            $mouvement->region = $vrac->acheteur->region;
                        }
                    }
                }

                $sv12->save();
            } catch (Exception $e) {
                echo sprintf("%s : %s\n", $e->getMessage(), $row->id);
                sleep(4);
                continue;
            }
        }
    }

}
