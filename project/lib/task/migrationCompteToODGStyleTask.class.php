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
    $c_id = $societe->_get('compte_societe');
    $comptes_todelete[] = $c_id;
    $c = CompteClient::getInstance()->find($c_id);
    if ($c) {
        $this->saveCompte($comptes_info, $societe->_id, $c);
    }

    $comptes_societe_etablissements = [$societe->compte_societe];
    $etablissements = [];
    foreach($societe->getEtablissementsObject(true, true) as $e) {
        $this->saveCompte($comptes_info, $e->_id, $e->getMasterCompte());
        $etablissements[] = $e;
        $comptes_societe_etablissements[] = $e->compte;
        $comptes_todelete[] = $e->compte;
    }

    $comptes_societe_etablissements;
    $comptes_new = [];

    $compte_interlocuteurs = [];
    foreach($societe->getComptesInterlocuteurs() as $c )  {
        $compte_interlocuteurs[$c->_id] = $c;
    }
    $societe->save();
    $societe = SocieteClient::getInstance()->find($societe->_id);

    $i = 0;
    foreach($compte_interlocuteurs as $id => $c) {
        print_r(['change l\'id de '.$id]);
        $old_id = $c->_id;
        $c = clone $c;
        $c->identifiant = sprintf('%s%02d', $societe->identifiant, 10 + $i++);
        $c->_id = 'COMPTE-'.$c->identifiant;
        $c->save();
        $comptes_todelete[] = $old_id;
        $comptes_new[$c->_id] = $c;
    }

    foreach($comptes_todelete as $id) {
        $c = CompteClient::getInstance()->find($id);
        if ($c) $c->delete();
    }

    foreach($etablissements as $e) {
        $e = EtablissementClient::getInstance()->find($e->_id);
        $this->restoreCompteToObj($comptes_info, $e->_id, $e);
        $e->save();
        $c = $e->getMasterCompte();
        $this->restoreCompte($comptes_info, $e->_id, $c);
        $comptes_new[$c->_id] = $c;
    }

    $societe = SocieteClient::getInstance()->find($societe->_id);
    $this->restoreCompte($comptes_info, $societe->_id, $societe->getMasterCompte());
    $this->restoreCompteToObj($comptes_info, $societe->_id, $societe->getMasterCompte());
    $societe->save();

  }

  private function saveCompte(&$comptes, $id, $compte) {
      $comptes[$id] = $compte->toArray();
      unset($comptes[$id]['_id']);
      unset($comptes[$id]['_rev']);
      unset($comptes[$id]['identifiant']);
      unset($comptes[$id]['compte_type']);
      unset($comptes[$id]['compte_type']);
      unset($comptes[$id]['id_societe']);
      unset($comptes[$id]['origines']);
      unset($comptes[$id]['societe_informations']);
      unset($comptes[$id]['type']);
      unset($comptes[$id]['adresse']);
      unset($comptes[$id]['adresse_complementaire']);
      unset($comptes[$id]['commune']);
      unset($comptes[$id]['code_postal']);
      unset($comptes[$id]['telephone']);
      unset($comptes[$id]['email']);
  }

  private function restoreCompteToObj(&$comptes, $id, $obj) {
      $compte = $comptes[$id];
      foreach(['telephone_bureau', 'telephone_mobile', 'telephone_perso', 'adresse', 'adresse_complementaire', 'civilite', 'code_postal', 'fax', 'telephone'] as $type) {
          if (isset($compte[$type]) && $compte[$type] && $obj->exist($type) && !$obj->get($type)) {
              $obj->set($type, $compte[$type]);
          }
      }
  }

  private function restoreCompte(&$comptes, $id, $compte) {
      if (!$compte) {
          return;
      }
      print_r(['rewrite', $compte->_id]);
      $compte = CompteClient::getInstance()->find($compte->_id);
      if ($compte) {
          foreach($comptes[$id] as $k => $v) {
              $compte->add($k, $v);
          }
          $compte->save();
      }
  }

}
