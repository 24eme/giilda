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
class tagAddManuelTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('debug', null, sfCommandOption::PARAMETER_OPTIONAL, 'use only one code creation', '0'),
            new sfCommandOption('compteid', null, sfCommandOption::PARAMETER_OPTIONAL, 'id du compte', false),
            new sfCommandOption('tag', null, sfCommandOption::PARAMETER_OPTIONAL, 'tag', false),
            new sfCommandOption('file', null, sfCommandOption::PARAMETER_OPTIONAL, 'import from file', false),
        ));

        $this->namespace = 'tag';
        $this->name = 'addManuel';
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
        if ($options['tag'] && $options['compteid']) {
            return $this->addTag($options['compteid'], $options['tag']);
        }
        if ($options['file']) {
            foreach(file($options['file']) as $line) {
                $args = split(';', $line);
                $this->addTag($args[0], $args[1]);
            }
            return ;
        }
        throw new sfException("bad arguments");
    }

    private function addTag($compteid,  $tag) {
        $compte = CompteClient::getInstance()->findByIdentifiant($compteid."01");
        if (!$compte) {
            echo "WARNING: compte $compteid not found\n";
            return false;
        }
        $compte->addTag('manuel', $tag);
        return $compte->save();
    }


}
