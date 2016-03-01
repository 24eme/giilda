<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeledeclarationMajContratsTeledeclaresTask
 *
 * @author mathurin
 */
class TeledeclarationMajContratsTeledeclaresTask  extends sfBaseTask
{
  protected function configure()
  {

      $this->addArguments(array(
       new sfCommandArgument('fieldName', sfCommandArgument::REQUIRED, "Nom du champs"),
          new sfCommandArgument('value', sfCommandArgument::REQUIRED, "Valeur du champs"),
    ));
      
    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'teledeclaration';
    $this->name             = 'majContratsTeledeclares';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [majContratsTeledeclares|INFO] task update teledeclared contrats.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    if(!isset($arguments['fieldName']) || !isset($arguments['value'])
            || (!$arguments['fieldName']) || (!$arguments['value'])){
        throw new sfException("le nom du champs et sa valeur sont obligatoire");
    }
    $fieldName = $arguments['fieldName'];
    $value = $arguments['value'];
    echo "### Début du maj contrat avec [$fieldName] => $value \n"; 
    $contrats = $this->getContratsTeledeclares();
    $this->majWithFieldName($contrats,$fieldName,$value);    
  }
  
  protected function getContratsTeledeclares() {
      return VracClient::getInstance()->retrieveAllVracsTeledeclares();
  }
  
  protected function majWithFieldName($contrats,$fieldName,$value) {
      if(!count($contrats)){
           echo "Aucun contrat télédeclaré\n"; 
      }
      
      foreach ($contrats->rows as $contratView) {
          $vrac = VracClient::getInstance()->find($contratView->id);
          $vrac->$fieldName = $value;
          $vrac->save();
          echo "Contrat ".$vrac->numero_contrat." [ ".$fieldName." ] => ".$vrac->$fieldName."\n"; 
      }
  }
}

