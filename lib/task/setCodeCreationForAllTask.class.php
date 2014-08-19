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
class setCodeCreationForAllTask extends sfBaseTask {

    protected $debug = false;

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('code_creation', sfCommandArgument::OPTIONAL, '0'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('debug', null, sfCommandOption::PARAMETER_OPTIONAL, 'use only one code creation', '0'),
        ));

        $this->namespace = 'teledeclaration';
        $this->name = 'setCodeCreationForAllTask';
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

        $this->debug = array_key_exists('debug', $options) && $options['debug'];
        $this->createCodeCreation($arguments);
    }

    public function createCodeCreation($arguments = array()) {
        $this->code_creation = null;
        
        if($this->debug){
            echo $this->yellow("WARNING => bebug Mode") . "\n";
            if (array_key_exists('code_creation', $arguments) 
                    && $arguments['code_creation'] 
                    && (!preg_match('/^[0-9]{4}$/', $arguments['code_creation']))) {
                throw new sfException("Les codes de création doivent faire 4 chiffres!");
                return false;
            }
            $this->code_creation = $arguments['code_creation'];            
            echo $this->yellow("tout les code de création seront assigné comme ") . $this->green($this->code_creation) . "\n";
        }
        

        $rows = CompteAllView::getInstance()->findByInterproVIEW("INTERPRO-inter-loire");

        $assignedComptes = array();
        foreach ($rows as $row) {
            $check = $this->checkErreurs($row);
            if(!$check){ continue; }
            
                $master_compte = $this->societe->getMasterCompte();
                if(!array_key_exists($master_compte->identifiant, $assignedComptes)){
                    $this->code_creation = ($this->debug)? sprintf("%04d",$this->code_creation) : sprintf("%04d",rand(0, 9999)); 
                    $master_compte->add('mot_de_passe', "{TEXT}".$this->code_creation);
                    $master_compte->save(false,false,false,true);
                    echo $this->societe->identifiant.";".$this->code_creation.";".$this->societe->raison_sociale_abregee.";".$this->societe->siege->adresse.";".$this->societe->siege->code_postal.";".$this->societe->siege->commune."\n";
                    $assignedComptes[$master_compte->identifiant] = $master_compte->identifiant;
                }
        }
    }

    public function checkErreurs($row) {
        $this->compte = CompteClient::getInstance()->find($row->id);
        if (!$this->compte) {
            echo $this->red("ERREUR : ") . "Le compte $row->id est introuvable en base.\n";
            return false;
        }
        $this->societe = $this->compte->getSociete();
        if (!$this->societe) {
            echo $this->red("ERREUR : ") . "Le compte $row->id n'appartient a aucune société.\n";
            return false;
        }
        $this->type_societe = $this->societe->type_societe;
        if (!$this->societe->type_societe) {
            echo $this->red("ERREUR : ") . "La societe $this->societe->_id n'a aucun type.\n";
            return false;
        }
        
        if (!$this->compte->isActif()) {
            echo $this->yellow("Compte inactif : ") . "Le compte $row->id est inactif.\n";
            return false;
        }
        return true;
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
