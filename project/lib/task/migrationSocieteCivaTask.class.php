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

    $object2delete[] = clone $societe;

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
    $societe->telephone_bureau = $compteSociete->telephone_bureau;
    $societe->telephone_mobile = $compteSociete->telephone_mobile;
    $societe->telephone_perso = $compteSociete->telephone_perso;
    $societe->fax = $compteSociete->fax;
    if(isset($compteSociete->extras->site_internet) && $compteSociete->extras->site_internet && !$compteSociete->site_internet) {
        $compteSociete->site_internet = $compteSociete->extras->site_internet;
    }
    $societe->site_internet = $compteSociete->site_internet;
    unset($societe->telephone);
    unset($societe->campagne_archive);
    unset($societe->numero_archive);
    unset($societe->cooperative);
    $object2save[$societe->_id] = $societe;

    if(SocieteClient::getInstance()->find($societe->_id, acCouchdbClient::HYDRATE_JSON)) {
        echo "La société existe déjà : ".$arguments['societe_id']." -> ".$societe->_id."\n";

        return;
    }

    if($compteSociete->type == "SOCIETE") {
        $oldCompteSociete = clone $compteSociete;
        unset($societe->contacts->{$oldCompteSociete->_id});
        $object2delete[$oldCompteSociete->_id] = $oldCompteSociete;
    }

    foreach($societe->contacts as $contact_id => $contact_info) {
        $compte = CompteClient::getInstance()->find($contact_id, acCouchdbClient::HYDRATE_JSON);
        $compte->id_societe = $societe->_id;
        if(isset($compte->extras->maison_mere_identifiant)) {
            $compte->extras->maison_mere_identifiant = $this->transformSocieteId($compte->extras->maison_mere_identifiant);
        }
        if(isset($compte->extras->site_internet) && $compte->extras->site_internet && !$compte->site_internet) {
            $compte->site_internet = $compte->extras->site_internet;
        }
        unset($compte->tags->documents);
        if($compte->_id == $compteSociete->_id) {
            $compte->mot_de_passe = null;
        }
        $object2save[$compte->_id] = clone $compte;
    }

    foreach($societe->etablissements as $etablissement_id => $etablissement_info) {
        $etablissement = EtablissementClient::getInstance()->find($etablissement_id, acCouchdbClient::HYDRATE_JSON);
        $etablissement->id_societe = $societe->_id;
        $etablissement->adresse = $object2save[$etablissement->compte]->adresse;
        $etablissement->adresse_complementaire = $object2save[$etablissement->compte]->adresse_complementaire;
        $etablissement->commune = $object2save[$etablissement->compte]->commune;
        $etablissement->code_postal = $object2save[$etablissement->compte]->code_postal;
        $etablissement->insee = $object2save[$etablissement->compte]->insee;
        $etablissement->pays = $object2save[$etablissement->compte]->pays;
        $etablissement->telephone_bureau = $object2save[$etablissement->compte]->telephone_bureau;
        $etablissement->telephone_mobile = $object2save[$etablissement->compte]->telephone_mobile;
        $etablissement->telephone_perso = $object2save[$etablissement->compte]->telephone_perso;
        $etablissement->fax = $object2save[$etablissement->compte]->fax;
        $etablissement->site_internet = $object2save[$etablissement->compte]->site_internet;
        unset($etablissement->telephone);
        $object2save[$etablissement->_id] = clone $etablissement;
    }

    $compteSociete->_id = "COMPTE-".$newIdentifiant;
    unset($compteSociete->_rev);
    $compteSociete->login = $compteSociete->identifiant;
    $compteSociete->identifiant = $newIdentifiant;
    $compteSociete->compte_type = "SOCIETE";
    $compteSociete->origines = [$societe->_id];
    $compteSociete->id_societe = "SOCIETE-".$newIdentifiant;
    if(isset($compteSociete->extras->maison_mere_identifiant)) {
        $compteSociete->extras->maison_mere_identifiant = $this->transformSocieteId($compteSociete->extras->maison_mere_identifiant);
    }
    unset($compteSociete->tags->documents);
    $object2save[$compteSociete->_id] = $compteSociete;

    if(isset($compteSociete->extras->maison_mere_identifiant) && $compteSociete->extras->maison_mere_identifiant && $compteSociete->extras->maison_mere_identifiant != $societe->_id) {
        $societe->societe_maison_mere = $compteSociete->extras->maison_mere_identifiant;
    }
    $societe->contacts->{$compteSociete->_id} = ['nom' => $compteSociete->nom_a_afficher, 'ordre' => 0];

    foreach($factures as $row) {
        $facture = FactureClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
        $object2delete[] = clone $facture;
        $facture->_id = str_replace($facture->identifiant, $newIdentifiant, $facture->_id);
        unset($facture->_rev);
        $facture->identifiant = $newIdentifiant;
        $object2save[$facture->_id] = $facture;
    }

    foreach($mandats as $id) {
        $mandat = MandatSepaClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
        $object2delete[] = clone $mandat;
        $mandat->_id = str_replace($mandat->debiteur->identifiant_rum, $newIdentifiant, $mandat->_id);
        unset($mandat->_rev);
        $object2save[$mandat->_id] = $mandat;
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
        if(!in_array($doc->type, ["Compte", "Etablissement", "Societe"])) {
            continue;
        }
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
    if(isset($compteSociete->extras->db2_num_tiers) && $compteSociete->extras->db2_num_tiers) {
        $newIdentifiant = sprintf("%06d", explode("|", $compteSociete->extras->db2_num_tiers)[0]);
    } else {
        $newIdentifiant = sprintf("%06d", $societe->code_comptable_client);
    }
    $this->idsTransformed[$id] = "SOCIETE-".$newIdentifiant;
    return $this->idsTransformed[$id];
  }
}
