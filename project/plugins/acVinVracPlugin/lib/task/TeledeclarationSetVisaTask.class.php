<?php

class TeledeclarationSetVisaTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'teledeclaration';
    $this->name             = 'setVisa';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [generateAlertes|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $context = sfContext::createInstance($this->configuration);
    
    $contrats = $this->getContratsValides();
    foreach ($contrats as  $contrat) {
        echo "changement de statut du contrat ".$contrat->id." \n";
        $vrac = VracClient::getInstance()->find($contrat->id);        
        $vrac->createVisa();    
        $vrac->save();
        echo "Nouveau numéro de visa ".$vrac->numero_archive."\n\n"; 
    }    
  }
  
  protected function getContratsValides() {
      return VracStatutAndTypeView::getInstance()->findContatsByStatut(VracClient::STATUS_CONTRAT_VALIDE);
  }
  
}
