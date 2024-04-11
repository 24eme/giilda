<?php

class exportSocieteTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'export';
    $this->name             = 'societe';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:societe|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    echo ExportSocieteCSV::getHeaderCsv();

    $cpt = 0;
    foreach(SocieteAllView::getInstance()->findByInterpro('INTERPRO-declaration') as $socdata) {
        $cpt++;
         if($cpt > 500) {
             sleep(2);
             $cpt = 0;
         }
        $soc = SocieteClient::getInstance()->find($socdata->id);

        $export = new ExportSocieteCSV($soc, false);
        echo $export->export();
    }
  }
}
