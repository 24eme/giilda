<?php

class TeledeclarationEnvoiEmailsContratsVisesTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'teledeclaration';
    $this->name             = 'envoiEmailsContratsVise';
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
    $this->sendEmails($contrats);
    
  }
  
  protected function getContratsValides() {
      return VracStatutAndTypeView::getInstance()->findContatsByStatut(VracClient::STATUS_CONTRAT_VISE);
  }
  
  protected function sendEmails($contrats) {
      if(!count($contrats)){
           echo "Aucun contrat en attente de Visa\n"; 
      }
      $vracEmailManager = new VracEmailManager($this->getMailer());
      foreach ($contrats as $contratView) {
          $vrac = VracClient::getInstance()->find($contratView->id);
          $vracEmailManager->setVrac($vrac);
          $vracEmailManager->sendMailContratVise();
          $vrac->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
          $vrac->save();
          echo "envoie des mails du contrat ".$vrac->numero_contrat." visa ".$vrac->numero_archive." (".$vrac->valide->statut.")\n"; 
      }
  }
}
