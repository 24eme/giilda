<?php

class societeMigateAllComptesTask extends migrateCompteTask
{
  protected $verbose = null;
  protected $withSave = null;
  protected $comptePrincipal = null;
  protected $comptesEtablissement = array();
  protected $comptesInterlocuteurs = array();

  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_OPTIONAL, 'isVerbose', '0'),
      new sfCommandOption('withSave', null, sfCommandOption::PARAMETER_OPTIONAL, 'isVerbose', '0'),
    ));
    // add your own options here
    $this->addArguments(array(
       new sfCommandArgument('societe_id', sfCommandArgument::REQUIRED, 'ID du societe')
    ));

    $this->namespace        = 'societe';
    $this->name             = 'migate-all-comptes';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony societe:migate-all-comptes SOCIETE-ID|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $context = sfContext::createInstance($this->configuration);

    $this->verbose = boolval($options["verbose"]);
    $this->withSave = boolval($options["withSave"]);
    $societe = SocieteClient::getInstance()->find($arguments['societe_id']);
    if (!$societe) {
      throw new sfException("Societe non trouvée : ".$arguments['societe_id']);
    }
    $compteSociete = $societe->getMasterCompte();

    $this->comptePrincipal = $this->getDocWithoutIdsInfos($compteSociete->toJson());
    foreach ($societe->getEtablissementsObj() as $idEtb => $etbStruct) {
        $this->comptesEtablissement[$idEtb] = $this->getDocWithoutIdsInfos($etbStruct->etablissement->getMasterCompte()->toJson());
    }

    foreach ($societe->getComptesInterlocuteurs() as $idInterlocuteurs => $interlocuteur) {
        $this->comptesInterlocuteurs[$idInterlocuteurs] = $this->getDocWithoutIdsInfos($interlocuteur->toJson());
    }

    $compteClient = CompteClient::getInstance();


    //removes and Deletes
    //suppression des interlocuteurs
    foreach ($societe->getComptesInterlocuteurs() as $idInterlocuteurs => $interlocuteur) {
        $societe->removeContact($idInterlocuteurs);
        $societe->save(false);
        //$compteClient->delete($interlocuteur);
        $societe = SocieteClient::getInstance()->find($societe->_id);
    }

    //suppression des comptes d'etablissements
    foreach ($societe->getEtablissementsObj() as $idEtb => $etbStruct) {
        $etbLocal = $etbStruct->etablissement;
        $etbLocal->add('compte_societe_saved',$this->getDocWithoutIdsInfos($etbLocal->getMasterCompte()->toJson()));
        $compteClient->delete($etbLocal->getMasterCompte());
        $etbLocal->save(false);
        $societe = SocieteClient::getInstance()->find($societe->_id);
    }

    //suppression du compte de master de la societe
    $societe->add('compte_societe_saved',$this->getDocWithoutIdsInfos($societe->getMasterCompte()->toJson()));
    $societe->save(false);
    $societe = SocieteClient::getInstance()->find($societe->_id);

    $masterCompteToDelete = $societe->getMasterCompte();
    $masterCompteIdToDelete = $masterCompteToDelete->_id;
    $compteClient->delete($masterCompteToDelete);
    $societe->compte_societe = null;
    $societe->contacts->remove($masterCompteIdToDelete);
    $societe->save(false);
    $societe = SocieteClient::getInstance()->find($societe->_id);

    //réinsertion des comptes

    //reinsertion du compte master


    $compteMasterJson = $this->comptePrincipal;
    $societe = SocieteClient::getInstance()->find($societe->_id);
    $societe->telephone_bureau = $compteMasterJson->telephone_bureau;
    $societe->telephone_mobile = $compteMasterJson->telephone_mobile;
    $societe->telephone_perso = $compteMasterJson->telephone_perso;
    $societe->fax = $compteMasterJson->fax;
    $societe->email = $compteMasterJson->email;
    $societe->site_internet = $compteMasterJson->site_internet;

    $societe->adresse = $compteMasterJson->adresse;
    $societe->adresse_complementaire = $compteMasterJson->adresse_complementaire;
    $societe->code_postal = $compteMasterJson->code_postal;
    $societe->commune = $compteMasterJson->commune;
    $societe->insee = $compteMasterJson->insee;
    $societe->pays = $compteMasterJson->pays;
    $societe->save();

    $societe = SocieteClient::getInstance()->find($societe->_id);
    $compteMasterSociete = $societe->getMasterCompte();
    foreach ($compteMasterJson as $key => $value) {
        $compteMasterSociete->add($key,$value);
    }
    $compteMasterSociete->save();

    $societe = SocieteClient::getInstance()->find($societe->_id);
    $societe->remove('compte_societe_saved');
    $societe->save();

    //reinsertion des comptes d'établissement
    foreach ($societe->getEtablissementsObj() as $idEtb => $etbStruct) {
        $etbLocal = $etbStruct->etablissement;
        $etbCompteLocal = $societe->findOrCreateCompteFromEtablissement($etbLocal);
        foreach ($etbLocal->compte_societe_saved as $key => $value) {
            $etbCompteLocal->add($key,$value);
        }
        $etbCompteLocal->save();

        $etbLocal->telephone_bureau = $etbCompteLocal->telephone_bureau;
        $etbLocal->telephone_mobile = $etbCompteLocal->telephone_mobile;
        $etbLocal->telephone_perso = $etbCompteLocal->telephone_perso;
        $etbLocal->fax = $etbCompteLocal->fax;
        $etbLocal->email = $etbCompteLocal->email;
        $etbLocal->site_internet = $etbCompteLocal->site_internet;

        $etbLocal->adresse = $etbCompteLocal->adresse;
        $etbLocal->adresse_complementaire = $etbCompteLocal->adresse_complementaire;
        $etbLocal->code_postal = $etbCompteLocal->code_postal;
        $etbLocal->commune = $etbCompteLocal->commune;
        $etbLocal->insee = $etbCompteLocal->insee;
        $etbLocal->pays = $etbCompteLocal->pays;


        $etbLocal->compte = $etbCompteLocal->_id;
        $etbLocal->remove('compte_societe_saved');
        $etbLocal->save();
        $societe = SocieteClient::getInstance()->find($societe->_id);
    }

    //reinsertion des interlocuteurs
    foreach ($this->comptesInterlocuteurs as $interlocuteur) {
        $newCompteInterlocuteur = CompteClient::getInstance()->createCompteInterlocuteurFromSociete($societe);
        foreach ($interlocuteur as $key => $value) {
            $newCompteInterlocuteur->add($key,$value);
        }
        $newCompteInterlocuteur->save();
        $societe = SocieteClient::getInstance()->find($societe->_id);
    }
    echo "Société ".$societe->_id." migrée\n";

  }

  public function getDocWithoutIdsInfos($doc){
      unset($doc->_id);
      unset($doc->_rev);
      unset($doc->type);
      unset($doc->identifiant);
      return $doc;
  }
}
