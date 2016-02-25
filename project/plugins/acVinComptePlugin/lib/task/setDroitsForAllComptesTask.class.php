<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of setDroitsForAllComptesTask
 *
 * @author mathurin
 */
class setDroitsForAllComptesTask extends sfBaseTask {

    protected $debug = false;

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('code_creation', sfCommandArgument::OPTIONAL, '0'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'teledeclaration';
        $this->name = 'setDroitsForAllComptesTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony teledeclaration:setCodeCreationForSupsendusTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
// initialize the database connection

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $this->createDroits();
    }

    public function createDroits() {
        $this->code_creation = null;

        $comptesWithoutDroits = $this->getCompteWithoutDroits();
        echo "ASSIGNEMENT DROITS \n\n\n";
        echo "###identifiant;code_creation;raison_sociale;raison_sociale_abregee;adresse;adresse_complementaire;code_postal;commune;type_societe;statut\n";
        foreach ($comptesWithoutDroits as $compteWithoutDroits) {
            $compte = CompteClient::getInstance()->findByIdentifiant($compteWithoutDroits);
            $compte->buildDroits();
            echo "NOUVEAU DROITS |";
            foreach ($compte->droits as $droit) {
                echo $droit . "|";
            }

            $compte->save(false, false, false, true);
            echo " ###" . $compte->_id . "\n";
        }
    }

    protected function getCompteWithoutDroits() {

        $rows = CompteAllView::getInstance()->findByInterproVIEW("INTERPRO-declaration");

        $comptesWithoutDroits = array();

        echo "---------------------\nTrie des Comptes pour droits\n";
        $mastersComptesAnalyses = array();

        foreach ($rows as $row) {
            $compte = CompteClient::getInstance()->find($row->id);
            if (!$compte) {
                echo $this->red("ERREUR : ") . "Le compte $row->id est introuvable en base.\n";
                continue;
            }
            $societe = $compte->getSociete();
            if (!$societe) {
                echo $this->red("ERREUR : ") . "Le compte $row->id n'appartient a aucune société.\n";
                continue;
            }

            $masterCompte = $societe->getMasterCompte();
            if (!array_key_exists($masterCompte->_id, $mastersComptesAnalyses)) {
                $mastersComptesAnalyses[$masterCompte->_id] = $masterCompte->_id;
                if ($masterCompte->exist("droits") && $masterCompte->droits && count($masterCompte->droits)) {
                    echo $this->yellow("WARNING : ") . "Le compte $masterCompte->identifiant possède les droits suivants : |";
                    foreach ($masterCompte->droits as $droit) {
                        echo $droit . "|";
                    }
                    echo "\n";

                    continue;
                } else {
                    echo $this->green("BRAVO : ") . "Le compte $masterCompte->identifiant n'a pas encore de droits \n";
                    $comptesWithoutDroits[$masterCompte->identifiant] = $masterCompte->identifiant;
                }
            }
        }
        return $comptesWithoutDroits;
    }

    public function green($string) {
        return "\033[32m" . $string . "\033[0m";
    }

    public function yellow($string) {
        return "\033[33m" . $string . "\033[0m";
    }

    public function red($string) {
        return "\033[31m" . $string . "\033[0m";
    }

}
