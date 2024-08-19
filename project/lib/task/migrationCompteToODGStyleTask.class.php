<?php

class migrationCompteToODGStyleTask extends sfBaseTask
{

  protected $idsTransformed = [];

  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('societe_id', sfCommandArgument::REQUIRED, "L'identifiant de la société"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'migration';
    $this->name             = 'compte-openodg';
    $this->briefDescription = 'Migration des comptes d\'une société vers de comptes OpenODG';
    $this->detailedDescription = <<<EOF
[migration:societe-civa|INFO] migration des compte d'une société suite au merge avec les contacts OpenODG
Call it with:

  [php symfony migration:societe-civa|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $comptes_todelete = [];
    $comptes_info = [];

    $societe = SocieteClient::getInstance()->find($arguments['societe_id']);
    $c = $societe->getMasterCompte();
    $this->saveCompte($comptes_info, $societe->_id, $c);

    $compte_interlocuteurs = [];
    foreach($societe->getComptesInterlocuteurs() as $c )  {
        $compte_interlocuteurs[$c->_id] = $c;
        $this->saveCompte($comptes_info, $c->_id, $c);
    }


    foreach($societe->getEtablissementsObject(true, true) as $e) {
        $this->saveCompte($comptes_info, $e->_id, $e->getMasterCompte());
        $etablissements[] = $e;
        $c = $e->getMasterCompte();
        $this->saveCompte($comptes_info, $e->_id, $c);
        $c->delete();
    }

    foreach($societe->getComptesInterlocuteurs() as $c )  {
        try {
            $c->delete();
        }catch(couchException $e) {}
    }

    $c = $societe->getMasterCompte();
    try {
        $c->delete();
    }catch(couchException $e) {}
    $societe->setMaintenance();
    $societe->compte_societe = null;
    $societe->remove('contacts');
    $societe->add('contacts');
    $societe->save();

    foreach($etablissements as $e) {
        $ne = EtablissementClient::getInstance()->find($e->_id);
        $ne->setMaintenance();
        $ne->set('compte', null);
        $ne->save();
    }

    $societe = SocieteClient::getInstance()->find($societe->_id);
    $societe->setMaintenance();
    $this->restoreCompteToObj($comptes_info, $societe->_id, $societe);
    $societe->save();
    $this->restoreCompte($comptes_info, $societe->_id, $societe->getMasterCompte());

    foreach($etablissements as $e) {
        $ne = EtablissementClient::getInstance()->find($e->_id);
        $this->restoreCompte($comptes_info, $e->_id, $ne->getMasterCompte());
    }

    foreach($compte_interlocuteurs as $id => $c) {
        $cnew = CompteClient::getInstance()->createCompteInterlocuteurFromSociete($societe);
        $cnew->setMaintenance();
        $this->restoreCompte($comptes_info, $id, $cnew);
        $cnew->save();
    }

  }

  private function saveCompte(&$comptes, $id, $compte) {
      $comptes[$id] = $compte->toArray();
      unset($comptes[$id]['_id']);
      unset($comptes[$id]['_rev']);
      unset($comptes[$id]['identifiant']);
      unset($comptes[$id]['compte_type']);
      unset($comptes[$id]['id_societe']);
      unset($comptes[$id]['origines']);
      unset($comptes[$id]['type']);
      unset($comptes[$id]['societe_informations']);
      unset($comptes[$id]['etablissement_informations']);
  }

  private function restoreCompteToObj(&$comptes, $id, $obj) {
      $compte = $comptes[$id];
      foreach(['telephone_bureau', 'telephone_mobile', 'telephone_perso', 'adresse', 'adresse_complementaire', 'civilite', 'code_postal', 'fax', 'telephone', 'region'] as $type) {
          if (isset($compte[$type]) && $compte[$type] && $obj->exist($type) && !$obj->get($type)) {
              $obj->set($type, $compte[$type]);
          }
      }
  }

  private function restoreCompte(&$comptes, $id, $compte) {
      if (!$compte) {
          return;
      }
      if ($compte->_rev) {
          $compte = CompteClient::getInstance()->find($compte->_id);
      }
      if ($compte) {
          foreach($comptes[$id] as $k => $v) {
              if ($v) {
                  $compte->add($k, $v);
              }
          }
          $compte->setMaintenance();
          $compte->save();
      }
  }

}
