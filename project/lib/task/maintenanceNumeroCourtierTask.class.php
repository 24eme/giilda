<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class maintenanceNumeroCourtierTask
 * @author mathurin
 */
class maintenanceNumeroCourtierTask extends sfBaseTask
{
  const CSV_COURTIER_ID = 0;
  const CSV_COURTIER_NUM = 1;
    
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
        new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv au format num_etb;num_courtier"),
    ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'courtier-numero';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceNumeroCourtier|INFO] task does things.
Call it with:

  [php symfony maintenanceNumeroCourtier|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    set_time_limit(0);
    $file = file($arguments['file']);
    
    echo "\n*** Mise à jour des numéros de courtier ***";
    $this->majNumCourtier($file);
    echo "\n*** FIN de mise à jour des numéros de courtier ***";
  }
  
  protected function majNumCourtier($file) {      
      foreach ($file as $line) {
                $datas = str_getcsv($line, ';');
                $etb_id = $datas[self::CSV_COURTIER_ID];
                $num_courtier = $datas[self::CSV_COURTIER_NUM];
                $etb = EtablissementClient::getInstance()->retrieveById($etb_id);
                if(!$etb){
                    echo "\n ===> WARNING courtier $etb_id non trouvé ***";
                }
                else
                {
                    $oldCartePro = $etb->carte_pro;
                    $etb->carte_pro = $num_courtier;
                    $etb->save(false,false,false);
                    echo "\n ===> Le courtier $etb_id a changé son numéro de carte ($oldCartePro) en $num_courtier ***";
                }
      }
  }
  
}