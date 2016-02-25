<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of updateCompteWithDroitsAndTypeSociete
 *
 * @author mathurin
 */
class addTagAutoTeledeclarantTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('debug', null, sfCommandOption::PARAMETER_OPTIONAL, 'use only one code creation', '0'),
        ));

        $this->namespace = 'teledeclaration';
        $this->name = 'addTagAutoTeledeclarant';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenance:update-comptes-with-droits|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
// initialize the database connection

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $this->addTagAutoTeledeclarant();
    }

    public function addTagAutoTeledeclarant() {       
        
        $comptesTeledecl = $this->getCompteTeledeclarants();
        echo "ASSIGNEMENT DES TAGS \n\n\n";
        foreach ($comptesTeledecl as $compteId) {
            
            $compte = CompteClient::getInstance()->findByIdentifiant($compteId);
            $compte->save();
            
            echo "###  ". $compte->_id ."\n";

        }
    }

    protected function getCompteTeledeclarants() {

        $rows = CompteAllView::getInstance()->findByInterproVIEW("INTERPRO-declaration");

        $comptesTeledeclarants = array();

        echo "---------------------\nTrie des Comptes pour code créations\n";
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

            if (!$compte->isActif()) {
                echo $this->red("ERREUR : ") . "Le est compte $row->id inactif.\n";
                continue;
            }


            $masterCompte = $societe->getMasterCompte();

            if (!$masterCompte->exist("droits")) {
                echo $this->red("ERREUR : ") . "Le compte $masterCompte->_id n'a aucun droits.\n";
                continue;
            }

            $hasTelededeclaration = $masterCompte->hasDroit(Roles::TELEDECLARATION);
            if (!$hasTelededeclaration) {
                echo $this->red("ERREUR : ") . "La societe $societe->_id n'a pas droit à la télédeclaration.\n";
                continue;
            }

            if ($societe->isViticulteur() || $societe->isCourtier() || $societe->isNegociant()) {
                if (!$masterCompte->exist("teledeclaration_active") || !$masterCompte->teledeclaration_active) {
                    echo $this->yellow("Warning : ") . "La societe $societe->_id n'est pas télédéclarante\n";
                    continue;
                }
            }

            if (!array_key_exists($masterCompte->identifiant, $comptesTeledeclarants)) {
                $comptesTeledeclarants[$masterCompte->identifiant] = $masterCompte->identifiant;
                echo $this->green("Teledeclarant : ") . "Le compte $masterCompte->identifiant est télédeclarant\n";
            }
        }
        return $comptesTeledeclarants;
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
