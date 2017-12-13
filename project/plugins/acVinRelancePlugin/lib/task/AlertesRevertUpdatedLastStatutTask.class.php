<?php

class AlertesRevertUpdatedLastStatutTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addArguments(array(
      new sfCommandArgument('relanceid', sfCommandArgument::REQUIRED, "Id de la relance à l'origine de la mis à jour des alertes. "),
      new sfCommandArgument('statut', sfCommandArgument::REQUIRED, "Statut dans lequel on veut remettre l'alerte. ")
  	));

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
    ));

    $this->namespace        = 'alertes';
    $this->name             = 'revert-updated-last-statut';
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
    $relanceid = $arguments['relanceid'];
    $statut = $arguments['statut'];
    if(!$relanceid){
      throw new sfException("Il faut spécifier un id de relance ayant modifier des statuts d'alertes");
    }
    if(!$statut){
      throw new sfException("Il faut spécifier un statut d'alerte");
    }

    $relance = RelanceClient::getInstance()->find($relanceid);
    if(!$relance){
      throw new sfException("La relance d'id $relanceid n'a pas été trouvée.");
    }

    foreach ($relance->getOrigines() as $key => $origineId) {
      $alerte = AlerteClient::getInstance()->find($origineId);
      if(!$alerte){
        echo "L'alerte $origineId n'a pas été trouvée en base\n";
      }
      if(!$alerte->isModifiable()){
        echo "L'alerte $origineId n'est pas modifiable\n";
        continue;
      }
      $s = $alerte->getStatut()->statut;
      echo "L'alerte $origineId est au statut $s ";
      $alerte->updateStatut($statut,'Annulation Génération');
      echo " => $statut \n";
      $alerte->save();
    }

  }
}
