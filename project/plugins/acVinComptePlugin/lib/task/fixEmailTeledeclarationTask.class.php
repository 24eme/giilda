<?php

class fixEmailTeledclarationTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('societe_doc_id', sfCommandArgument::REQUIRED, 'Société document id'),
    ));


    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'fix';
    $this->name             = 'email-teledeclaration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $societe = SocieteClient::getInstance()->find($arguments['societe_doc_id']);

    if(!$societe->isTransaction()) {
      return;
    }

    $compte = $societe->getMasterCompte();

    if(!$compte->exist('teledeclaration_active') || !$compte->teledeclaration_active) {
        return;
    }

    $etablissement = $societe->getEtablissementPrincipal();

    if(!$etablissement) {
      return;
    }

    if($etablissement->getEmailTeledeclaration()) {
      return;
    }

    $email = $societe->getEmailTeledeclaration();

    if(!$email) {
        echo "ERROR;".$societe->_id.";".$societe->raison_sociale."\n";
        return;
    }

    $etablissement->add('teledeclaration_email', $email);
    //$etablissement->save();

    $allEtablissements = $societe->getEtablissementsObj();
    foreach ($allEtablissements as $etablissementObj) {
        $etb = $etablissementObj->etablissement;        
        $etb->add('teledeclaration_email', $email);
        //$etb->save();
    }

    echo "UPDATE;$societe->_id;$societe->raison_sociale;$email\n";
        
  }
}
