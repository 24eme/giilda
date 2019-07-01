<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of addDroitForAllComptesTask
 *
 * @author mathurin
 */
class addDroitForAllComptesTask extends sfBaseTask {

    protected $debug = false;

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('droit', sfCommandArgument::OPTIONAL, '0'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_OPTIONAL, 'type', null),
        ));

        $this->namespace = 'teledeclaration';
        $this->name = 'addDroitForAllComptes';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [addDroitForAllComptesTask|INFO] task does things.
Call it with:

  [php symfony teledeclaration:addDroitForAllComptesTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if (!isset($arguments['droit']) || !$arguments['droit']) {
          throw new sfException("il faut spécifier un droit");
        }
        $droit = $arguments['droit'];

        $rowsCompte = CompteAllView::getInstance()->findByInterproAndStatutVIEW("INTERPRO-inter-loire", CompteClient::STATUT_ACTIF);
        foreach ($rowsCompte as $compteView) {
          $compte = CompteClient::getInstance()->find($compteView->id);
          if($compte->hasDroit(Roles::TELEDECLARATION_VRAC) || $compte->hasDroit(Roles::TELEDECLARATION_DRM)){
            $compte->getOrAdd('droits')->add(Roles::TELEDECLARATION_FACTURE, Roles::TELEDECLARATION_FACTURE);
            $compte->save();
            echo "Ouverture du droit $droit ".$this->green($compte->_id)."\n";
          }else{
            echo "Pas d'ouverture $droit ".$this->yellow($compte->_id)."\n";
          }
        }
        echo "FIN\n";
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
