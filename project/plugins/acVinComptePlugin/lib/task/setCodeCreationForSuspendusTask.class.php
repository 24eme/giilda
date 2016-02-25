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
class setCodeCreationForSuspendusTask extends sfBaseTask {

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
        $this->name = 'setCodeCreationForSupsendusTask';
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

        $this->createCodeCreation($arguments);
    }

    public function createCodeCreation($arguments = array()) {
        $this->code_creation = null;

        $comptesCDC = $this->getCompteCodeCreation();
        echo "ASSIGNEMENT CODE DE CREATIONS \n\n\n";
        echo "###identifiant;code_creation;raison_sociale;raison_sociale_abregee;adresse;adresse_complementaire;code_postal;commune;type_societe;statut\n";
        foreach ($comptesCDC as $compteCDCId) {
            $this->code_creation = sprintf("%04d", rand(0, 9999));
            $compte = CompteClient::getInstance()->findByIdentifiant($compteCDCId);

            $compte->add('mot_de_passe', "{TEXT}" . $this->code_creation);
            $compte->save(false, false, false, true);
            $societe = $compte->getSociete();
            echo "###" . $societe->identifiant . ";" . $this->code_creation . ";" . $societe->raison_sociale . ";" . $societe->raison_sociale_abregee . ";" . $societe->siege->adresse . ";" . $societe->siege->adresse_complementaire . ";" . $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";".$compte->statut."\n";
        }
    }

    protected function getCompteCodeCreation() {

        $rows = CompteAllView::getInstance()->findByInterproVIEW("INTERPRO-declaration");

        $comptesCodeCreation = array();

        echo "---------------------\nTrie des Comptes pour code créations\n";
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
                if ($masterCompte->exist("mot_de_passe") && $masterCompte->mot_de_passe && $masterCompte->mot_de_passe !== "") {
                    if (substr($masterCompte->mot_de_passe, 0, 6) === "{TEXT}") {
                        $code = str_replace("{TEXT}", "", $masterCompte->mot_de_passe);
                        echo $this->yellow("WARNING : ") . "Le compte $masterCompte->identifiant est actif et possède un code de création : $code. \n";
                    } else {
                        echo $this->yellow("WARNING : ") . "Le compte $masterCompte->identifiant est actif et possède un mdp ou un mdp oublié \n";
                    }
                    continue;
                } else {
                    echo $this->green("BRAVOO : ") . "Le compte $masterCompte->identifiant ne possède pas encore de code de création \n";
                    $comptesCodeCreation[$masterCompte->identifiant] = $masterCompte->identifiant;
                }
            }
        }
        return $comptesCodeCreation;
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
