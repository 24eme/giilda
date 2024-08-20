<?php

class societeMigateVerifComptesTask extends migrateCompteTask
{
  protected $verbose = null;
  protected $withSave = null;
  protected $analyseStr = "";

  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));
    // add your own options here
    $this->addArguments(array(
       new sfCommandArgument('societe_id', sfCommandArgument::REQUIRED, 'ID du societe')
    ));

    $this->namespace        = 'societe';
    $this->name             = 'migate-verif-comptes';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony societe:migate-verif-comptes SOCIETE-ID|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $context = sfContext::createInstance($this->configuration);

    $this->verbose = boolval($options["verbose"]);
    $societe = SocieteClient::getInstance()->find($arguments['societe_id']);
    if (!$societe) {
      throw new sfException("Societe non trouvée : ".$arguments['societe_id']);
    }
    $compteSociete = $societe->getMasterCompte();
    $societeJson = $societe->toJson();
    foreach ($compteSociete->toJson() as $key => $value) {
      if(!in_array($key,array("_rev","type"))){
        $this->analyse($key,$value,$societeJson);
      }
    }
    if($this->analyseStr){ echo $this->analyseStr; }else{ echo "OK"; }
  }

  protected function analyse($key,$v,$societeJson){
    if($v != null){
      $isnode = !is_string($v) && (is_array($v) || get_class($v) == "stdClass");
      if($isnode){
        foreach ($v as $k => $newv) {
          $this->analyse($k,$newv,$societeJson);
        }
      }else{
          $v_protected = preg_quote($v);
          if(!preg_match("|$v_protected|",serialize($societeJson))){
            $this->analyseStr.= $key." : ".$v." \n";
          }
        }
      }
    }

}
