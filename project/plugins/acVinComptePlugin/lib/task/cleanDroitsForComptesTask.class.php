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
class cleanDroitsForComptesTask extends sfBaseTask {

    protected $debug = false;

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('doc_id', sfCommandArgument::OPTIONAL, '0'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
        ));

        $this->namespace = 'teledeclaration';
        $this->name = 'cleanDroitsForComptesTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [setDroitsForAllComptesTask|INFO] task does things.
Call it with:

  [php symfony teledeclaration:setCodeCreationForSupsendusTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
// initialize the database connection

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $compteId = $arguments['doc_id'];
        if($compteId){
          $compte = CompteClient::getInstance()->find($compteId);
          $clean = $compte->cleanDroits();
          if($clean){
            echo $this->green($compteId)." a subit un clean de ses droits\n";
            echo $this->green("nouveaux droits")." ".implode($compte->get("droits")->toArray(0,1),'|');
            echo "\n";
          }
        }else{

            $rowsCompte = CompteAllView::getInstance()->findByInterproAndStatutVIEW("INTERPRO-inter-loire", CompteClient::STATUT_ACTIF);
            foreach ($rowsCompte as $compteView) {
                $compte = CompteClient::getInstance()->find($compteView->id);
                if($compte->isActif()){
                  $clean = $compte->cleanDroits();
                  if($clean){
                    echo $this->green($compte->_id)." a subit un clean de ses droits\n";
                    echo $this->green("nouveaux droits")." ".implode($compte->get("droits")->toArray(0,1),'|');
                    echo "\n";
                  }
                }
            }
            echo "FIN\n";
    }
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
