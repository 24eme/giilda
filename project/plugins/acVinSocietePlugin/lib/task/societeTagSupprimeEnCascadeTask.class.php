<?php

class societeTagSupprimeEnCascadeTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),

      new sfCommandOption('only-suspendus', null, sfCommandOption::PARAMETER_OPTIONAL, 'Seulement les entités suspendues'),
    ));

    $this->addArguments(array(
       new sfCommandArgument('societe_id', sfCommandArgument::REQUIRED, 'ID de la societe')
    ));

    $this->namespace        = 'societe';
    $this->name             = 'tag-supprime-en-cascade';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony societe:tag-supprime-en-cascade|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    sfConfig::set('app_compte_synchro', false);

    $societe = SocieteClient::getInstance()->find($arguments['societe_id']);
    $onlySuspendus = $options['only-suspendus'];

    if (!$societe) {
        echo "ERROR societe not found ".$arguments['societe_id']."\n";
        return;
    }

    if ($onlySuspendus && $societe->statut != SocieteClient::STATUT_SUSPENDU) {
        echo "ERROR societe non suspendue ".$arguments['societe_id']."\n";
        return;
    }

    foreach ($societe->getContactsObj() as $compte) {
        $compte->statut = CompteClient::STATUT_SUPPRIME;
        $compte->save();
        echo "SUCCESS statut compte supprimé ".$compte->_id."\n";
    }

    foreach($societe->getEtablissementsObj() as $item) {
        $etablissement = $item->etablissement;
        $etablissement->statut = EtablissementClient::STATUT_SUPPRIME;
        $etablissement->save();
        echo "SUCCESS statut etablissement supprimé ".$etablissement->_id."\n";
    }

    $societe->statut = SocieteClient::STATUT_SUPPRIME;
    $societe->save();
    echo "SUCCESS statut societe supprimé ".$societe->_id."\n";

  }
}
