<?php

class TeledeclarationEnvoiEmailsContratsTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                new sfCommandOption('type_mail', null, sfCommandOption::PARAMETER_REQUIRED, "Type d'email a envoyer", null),
                new sfCommandOption('vrac_id', null, sfCommandOption::PARAMETER_REQUIRED, 'Identifiant du contrat dont il faut envoyer le mail ', null),
      // add your own options here
    ));

    $this->namespace        = 'teledeclaration';
    $this->name             = 'envoiEmailsContrats';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
Par défaut, cette tache envoie les mails pour les contrats visés pour tous ceux qui sont en attente de visa.

Il est possible de spécifier un identifiant et un autre type de mail à envoyer.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $routing = clone ProjectConfiguration::getAppRouting();
    $context = sfContext::createInstance($this->configuration);
    $context->set('routing', $routing);
    if (!$options['type_mail'] && !$options['vrac_id']) {
        $type = 'vise';
        $contrats = array();
        foreach ($contrats as $contratView) {
            $contrats[] = $contratView->id;
        }
    }else {
        if ($options['vrac_id']) {
            $contrats = array($options['vrac_id']);
        }
        if ($options['type_mail']) {
            $type = $options['type_mail'];
        }
    }
    if (count($contrats) && $type) {
        $this->sendEmails($contrats, $type);
    }
  }

  protected function getContratsValides() {
      return VracStatutAndTypeView::getInstance()->findContatsByStatut(VracClient::STATUS_CONTRAT_VISE);
  }

  protected function sendEmails($contrats, $type) {
      if(!count($contrats)){
           echo "Aucun contrat en attente de Visa\n";
      }
      $vracEmailManager = new VracEmailManager($this->getMailer());
      foreach ($contrats as $cid) {
          $vrac = VracClient::getInstance()->find($cid);
          if (!$vrac) {
              continue;
          }
          $vracEmailManager->setVrac($vrac);
          if ($type == 'vise') {
              $vracEmailManager->sendMailContratVise();
              $vrac->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
              $vrac->save();
          }elseif ($type == 'attente') {
              $vracEmailManager->sendMailAttenteSignature();
          }elseif ($type == 'annule') {
              $vracEmailManager->sendMailAnnulation();
          }
          echo "Envoi des mails $type pour le contrat ".$vrac->numero_contrat." / ".$vrac->numero_archive." (".$vrac->valide->statut.")\n";
      }
  }
}
