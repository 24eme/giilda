<?php

class migrationSocieteCivaTask extends sfBaseTask
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
    $this->name             = 'societe-civa';
    $this->briefDescription = 'Migration des sociétés du CIVA';
    $this->detailedDescription = <<<EOF
[migration:societe-civa|INFO] migration des sociétés du CIVA
Call it with:

  [php symfony migration:societe-civa|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $object2save = [];
    $object2delete = [];
    $societe = SocieteClient::getInstance()->find($arguments['societe_id'], acCouchdbClient::HYDRATE_JSON);
    if(!$societe) {
        return;
    }
    if($societe->code_comptable_client == $societe->identifiant) {
        echo "Le code comptable est déjà égal à l'identifiant de la société : ".$societe->_id."\n";

        return;
    }


    $compteSociete = CompteClient::getInstance()->find($societe->compte_societe, acCouchdbClient::HYDRATE_JSON);
    $factures = FactureSocieteView::getInstance()->findBySociete($societe);
    $mandats = acCouchdbManager::getClient()->startkey_docid(sprintf(MandatSepaClient::TYPE_COUCHDB."-%s-%s", $societe->identifiant, "00000000"))->endkey_docid(sprintf(MandatSepaClient::TYPE_COUCHDB."-%s-%s", $societe->identifiant, "99999999"))->execute(acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();

    $object2save[] = $societe;
    $object2delete[] = clone $societe;
    $object2save[] = $compteSociete;

    $newIdentifiant = str_replace("SOCIETE-", "", $this->transformSocieteId($arguments['societe_id']));

    if($newIdentifiant == "000000") {
        echo "Aucun nouvelle identifiant trouvée : ".$societe->_id."\n";

        return;
    }

    unset($societe->_rev);
    $societe->_id = "SOCIETE-".$newIdentifiant;
    $societe->identifiant = $newIdentifiant;
    $societe->compte_societe = "COMPTE-".$newIdentifiant;
    if(isset($societe->societes_liees)) {
        $newSocietesLiees = [];
        foreach($societe->societes_liees as $id) {
            $newSocietesLiees[] = $this->transformSocieteId($id);
        }
        $societe->societes_liees = $newSocietesLiees;
    }

    if(SocieteClient::getInstance()->find($societe->_id, acCouchdbClient::HYDRATE_JSON)) {
        echo "La société existe déjà : ".$arguments['societe_id']." -> ".$societe->_id."\n";

        return;
    }

    if($compteSociete->type == "SOCIETE") {
        $oldCompteSociete = clone $compteSociete;
        $oldCompteSociete->compte_type = "INTERLOCUTEUR";
        $oldCompteSociete->fonction = "Societe";
        $oldCompteSociete->origines = [];

        $object2save[] = $oldCompteSociete;
    }

    foreach($societe->etablissements as $etablissement_id => $etablissement_info) {
        $etablissement = EtablissementClient::getInstance()->find($etablissement_id, acCouchdbClient::HYDRATE_JSON);
        $etablissement->id_societe = $societe->_id;
        $object2save[] = clone $etablissement;
    }
    foreach($societe->contacts as $contact_id => $contact_info) {
        $compte = CompteClient::getInstance()->find($contact_id, acCouchdbClient::HYDRATE_JSON);
        $compte->id_societe = $societe->_id;
        if(isset($compte->extras->maison_mere_identifiant)) {
            $compte->extras->maison_mere_identifiant = $this->transformSocieteId($compte->extras->maison_mere_identifiant);
        }
        $object2save[] = clone $compte;
    }

    $compteSociete->_id = "COMPTE-".$newIdentifiant;
    unset($compteSociete->_rev);
    $compteSociete->identifiant = $newIdentifiant;
    $compteSociete->compte_type = "SOCIETE";
    $compteSociete->origines = [$societe->_id];
    $compteSociete->id_societe = "SOCIETE-".$newIdentifiant;
    if(isset($compteSociete->extras->maison_mere_identifiant)) {
        $compteSociete->extras->maison_mere_identifiant = $this->transformSocieteId($compteSociete->extras->maison_mere_identifiant);
    }

    $societe->contacts->{$compteSociete->_id} = ['nom' => $compteSociete->nom_a_afficher, 'ordre' => 0];

    foreach($factures as $row) {
        $facture = FactureClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
        $object2delete[] = clone $facture;
        $facture->_id = str_replace($facture->identifiant, $newIdentifiant, $facture->_id);
        unset($facture->_rev);
        $facture->identifiant = $newIdentifiant;
        $object2save[] = $facture;
    }

    foreach($mandats as $id) {
        $mandat = MandatSepaClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
        $object2delete[] = clone $mandat;
        $mandat->_id = str_replace($mandat->debiteur->identifiant_rum, $newIdentifiant, $mandat->_id);
        unset($mandat->_rev);
        $object2save[] = $mandat;
    }

    foreach($object2save as $doc) {
        echo $doc->_id." changed\n";
        acCouchdbManager::getClient()->storeDoc($doc);
    }

    foreach($object2delete as $doc) {
        echo $doc->_id." deleted\n";
        acCouchdbManager::getClient()->deleteDoc($doc);
    }

    foreach($object2save as $doc) {
        echo $doc->_id." saved with model\n";
        $doc = acCouchdbManager::getClient()->find($doc->_id);
        $doc->save();
    }
  }

  protected function transformSocieteId($id) {
    if(isset($this->idsTransformed[$id])) {

        return $this->idsTransformed[$id];
    }
    $societe = SocieteClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);

    if(!$societe) {
        $societe = CompteClient::getInstance()->find(str_replace("SOCIETE-", "COMPTE-", $id))->getSociete();
    }
    if(!$societe) {
        throw new Exception("Societe ".$id." non trouvé");
    }
    $compteSociete = CompteClient::getInstance()->find($societe->compte_societe, acCouchdbClient::HYDRATE_JSON);
    if($compteSociete->extras->db2_num_tiers) {
        $newIdentifiant = sprintf("%06d", explode("|", $compteSociete->extras->db2_num_tiers)[0]);
    } else {
        $newIdentifiant = sprintf("%06d", $societe->code_comptable_client);
    }
    $this->idsTransformed[$id] = "SOCIETE-".$newIdentifiant;
    return $this->idsTransformed[$id];
  }
}