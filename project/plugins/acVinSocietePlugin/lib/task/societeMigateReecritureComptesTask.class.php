<?php

class societeMigateReecritureComptesTask extends migrateCompteTask
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
    $this->name             = 'migate-reecriture-comptes';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony societe:migate-reecriture-comptes SOCIETE-ID|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $context = sfContext::createInstance($this->configuration);

    $societe = SocieteClient::getInstance()->find($arguments['societe_id']);
    if (!$societe) {
      throw new sfException("Societe non trouvée : ".$arguments['societe_id']);
    }
    $compteSociete = $societe->getMasterCompte();
    $societe->contacts->remove($compteSociete->_id);
    $newCompteMaster = CompteClient::getInstance()->createCompteMasterFromSociete($societe,true);
    $societe->contacts->remove($compteSociete->_id);

    $savedCompteInSociete = $societe->compte_societe_saved;
    foreach ($savedCompteInSociete->toJson() as $key => $value) {
      $newCompteMaster->add($key,$value);
    }
    $newCompteMaster->save();
    $societe->compte_societe = $newCompteMaster->_id;

    $societe->contacts->add($newCompteMaster->_id)->nom = null;
    $societe->remove('compte_societe_saved');
    $societe->save();

    $compteSociete->delete();
    echo "OK";
  }

  protected function analyse($key,$v,$societeJson){
    if($v != null){
      $isnode = !is_string($v) && (is_array($v) || get_class($v) == "stdClass");
      if($isnode){
        foreach ($v as $k => $newv) {
          $this->analyse($k,$newv,$societeJson);
        }
      }else{
          if(!preg_match("/$v/",serialize($societeJson))){
            $this->analyseStr.= $key." : ".$v." \n";
          }
        }
      }
    }

}
