<?php

class daeRapportDepotsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'application'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'dae';
        $this->name = 'rapport-depots';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $items = DAEClient::getInstance()->findAll();
        $periodes = [];
        $depots = [];
        foreach ($items as $item) {
          if (!isset($depots[$item->identifiant])) {
              $depots[$item->identifiant] = [];
          }
          $periode = substr($item->date, 0, -3);
          $periodes[$periode] = $periode;
          if (!in_array($periode, $depots[$item->identifiant])) {
              $depots[$item->identifiant][] = $periode;
          }
        }
        ksort($periodes);
        echo "Etablissement";
        foreach($periodes as $periode) {
            echo ";$periode";
        }
        echo "\n";
        foreach($depots as $id => $periodesDepot) {
            echo $id;
            foreach($periodes as $periode) {
                if (in_array($periode, $periodesDepot)) {
                    echo ";oui";
                } else {
                    echo ";";
                }
            }
            echo "\n";
        }
    }

}
