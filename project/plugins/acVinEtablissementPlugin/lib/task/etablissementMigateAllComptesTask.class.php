<?php

class etablissementMigateAllComptesTask extends migrateCompteTask
{

  protected $verbose = null;
  protected $withSave = null;

  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('all', null, sfCommandOption::PARAMETER_OPTIONAL, 'Display all societé (suspendu included)', ''),
      new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_OPTIONAL, 'isVerbose', '0'),
      new sfCommandOption('withSave', null, sfCommandOption::PARAMETER_OPTIONAL, 'withSave', '0'),
    ));
    // add your own options here
    $this->addArguments(array(
       new sfCommandArgument('etablissement_id', sfCommandArgument::REQUIRED, 'ID de l\'etablissement')
    ));

    $this->namespace        = 'etablissement';
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
    $etablissement = EtablissementClient::getInstance()->find($arguments['etablissement_id']);
    if (!$etablissement) {
      throw new sfException("Etablissement non trouvé : ".$arguments['etablissement_id']);
    }
    $compteEtablissement = $etablissement->getMasterCompte();
    $compteEtablissementJson = $compteEtablissement->toJson();

    $isCompteSociete = ($compteEtablissementJson->compte_type == "SOCIETE");
    $isCompteEtablissement = ($compteEtablissementJson->compte_type == "ETABLISSEMENT");

    $societe = $etablissement->getSociete();

    if(!$this->withSave && !$this->verbose){
      echo "$etablissement->_id / $compteEtablissement->_id analyse... ";
      if($isCompteSociete){
        echo " compte de type SOCIETE";
      }
      if($isCompteEtablissement){
        echo " compte de type ETABLISSEMENT";
      }
      echo "\n";
    }
    foreach ($compteEtablissementJson as $key => $value) {

      if($key == 'origines'){
        if($this->verbose){
          echo "$etablissement->_id : Le compte $compteEtablissement->_id a pour origines : ".implode(',',$value)."\n";
        }
      }
      $this->verifySocieteDuplicatedInfos($compteEtablissement,$societe,$key,$value,$this->verbose);
      if($isCompteSociete){
        $this->verifyAdresseSociete($compteEtablissement,$societe,$key,$value,$this->verbose);
      }
      $this->displayGroupesTagsAndDroits($compteEtablissement,$societe,$key,$value,$this->verbose);


      if($key == 'compte_type'){
        if($isCompteSociete && "SOCIETE" != $value){
          throw new sfException("La type du compte $compteEtablissement->_id n'est pas SOCIETE ! : ".$value);
        }
        if($isCompteEtablissement && "ETABLISSEMENT" != $value){
          throw new sfException("La type du compte $compteEtablissement->_id n'est pas SOCIETE ! : ".$value);
        }

        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour type : ".$value."\n";
        }
      }

      if($key == 'lat'){
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour site lat : ".$value."\n";
        }
      }
      if($key == 'lon'){
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour site lon : ".$value."\n";
        }
      }
      if($key == "societe_informations"){
        $this->verifySocieteInformationNode($compteEtablissement,$societe,$value,$this->verbose);
      }

      if($key == "etablissement_informations"){
        $fields = get_object_vars($value);
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour etablissement informations (".implode(",",array_keys($fields)).") [cvi=$value->cvi, ppm=$value->ppm] \n";
        }
        if(count($fields) > 2){
          throw new sfException("Le nombre de champs d'etablissement information du compte $compteEtablissement->_id est trop grand $societe->_id : ".implode(",",$fields));
        }
      }
      if($key == 'interpro'){
        if($societe->interpro != $value){
          throw new sfException("L'interpro du compte $compteEtablissement->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
        }
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour interpro : ".$value."\n";
        }
      }
      if($key == 'statut'){
        if(!is_null($societe->statut) && ($societe->statut != $value)){
          throw new sfException("Le statut du compte $compteEtablissement->_id n'est pas la même que celle dans la société $societe->_id : ".$value);
        }
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour statut : ".$value."\n";
        }
      }
      if($key == 'teledeclaration_active'){
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour teledeclaration_active_compte : ".$value."\n";
        }
      }
      if($key == 'date_modification'){
        if($this->verbose){
          echo "$societe->_id : Le compte $compteEtablissement->_id a pour date_modification : ".$value."\n";
        }
      }


      $etablissement->add("numero_interne_compte",$compteEtablissement->num_interne);
      $etablissement->add("civilite_compte",$compteEtablissement->civilite);
      $etablissement->add("prenom_compte",$compteEtablissement->prenom);
      $etablissement->add("nom_compte",$compteEtablissement->nom);
      $etablissement->add("nom_a_afficher_compte",$compteEtablissement->nom_a_afficher);
      $etablissement->add("fonction_compte",$compteEtablissement->fonction);
      $etablissement->add("commentaire_compte",$compteEtablissement->commentaire);
      $etablissement->add("mot_de_passe",$compteEtablissement->mot_de_passe);
      $etablissement->add("insee_compte",$compteEtablissement->insee);
      $etablissement->add("telephone_perso",$compteEtablissement->telephone_perso);
      $etablissement->add("telephone_bureau",$compteEtablissement->telephone_bureau);
      $etablissement->add("telephone_mobile",$compteEtablissement->telephone_mobile);
      $etablissement->add("fax",$compteEtablissement->fax);
      $etablissement->add("site_internet",str_replace("\n", '',$compteEtablissement->site_internet));
      $etablissement->add("lat",$compteEtablissement->lat);
      $etablissement->add("lon",$compteEtablissement->lon);
      $etablissement->add("groupes",$compteEtablissement->groupes);
      $etablissement->add("tags",$compteEtablissement->tags);
      if($compteEtablissement->exist("teledeclaration_active")){
        $societe->add("teledeclaration_active_compte",$compteEtablissement->teledeclaration_active);
      }
      $etablissement->add("date_modification_compte",$compteEtablissement->date_modification);
      if(!in_array($key,self::$list_fields_analysed)){
        throw new sfException("Le champs $key du compte n'a pas été analysé ");
      }

    }
    if($this->withSave){
      echo "Save $etablissement->_id avec les infos de son compte \n";
      $etablissement->save();
    }
  }
}
