<?php

class daeDeleteTask extends sfBaseTask {

    protected function configure() {
      	$this->addArguments(array(
          new sfCommandArgument('etablissementid', sfCommandArgument::REQUIRED, 'Identifiant etablissement'),
          new sfCommandArgument('periode', sfCommandArgument::REQUIRED, 'Periode'),
      	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'application'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'dae';
        $this->name = 'delete';
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
        $items = DAEClient::getInstance()->findByIdentifiantPeriode(str_replace('ETABLISSEMENT-', '', $arguments['etablissementid']), str_replace('-', '', $arguments['periode']), acCouchdbClient::HYDRATE_JSON)->getDatas();
        $i = 0;
        $nb = count($items);
        foreach ($items as $item) {
          $i++;
          $pourc = floor($i / $nb * 100);
          $dae = DAEClient::getInstance()->find($item->_id);
          $dae->delete();
          $this->logSection("delete", $item->id." supprimé avec succès - $i / $nb ($pourc%)", null, 'SUCCESS');
        }
    }

}
