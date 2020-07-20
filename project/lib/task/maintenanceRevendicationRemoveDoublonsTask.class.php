<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maintenanceDRMMouvementsUpdateTask
 *
 * @author mathurin
 */
class maintenanceRevendicationRemoveDoublonsTask extends sfBaseTask {

  const CAMPAGNE=1;
  const IDENTIFIANT=2;
  const PRODUIT=3;
  const CODE_PRODUIT=4;
  const REGION=5;
  const ID=6;
  const VOLUME=7;
  const NOM_DECLARANT=8;
  const LIBELLE_PRODUIT=9;
  const IS_DOUBLON=10;

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('id_revendication', sfCommandArgument::REQUIRED, 'REVENDICATION'),
            new sfCommandArgument('file_path', sfCommandArgument::REQUIRED, 'Chemin du fichier de doublon'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'revendication-remove-doublons';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
[maintenanceRevendicationRemoveDoublonTask|INFO] supprime les doublons de l'object Revendication à partir des doublons listés dans le fichier
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $id_revendication = $arguments['id_revendication'];
        if(!$id_revendication){
            throw new sfException("L'identifiant d'un document de revendication est necessaire");
        }

        $revendication = RevendicationClient::getInstance()->find($id_revendication);

        if(!$revendication){
            throw new sfException("La Revendication $id_revendication n'existe pas en base");
        }

        $file = file($arguments['file_path']);
        if(!$file){
            throw new sfException("Le fichier de doublon est necessaire");
        }
        $campagne = $revendication->campagne;
        $line_num = 0;
        foreach ($file as $line) {
            $line_num++;
            if(preg_match("/^#REVENDIQUE/",$line)){
              continue;
            }
            $datas = str_getcsv($line, ';');

            if($datas[self::CAMPAGNE] != $campagne){
              echo $line_num." => la campagne est différente de celle de la revendication [".$datas[self::CAMPAGNE]."]\n";
              continue;
            }

            if($datas[self::IS_DOUBLON] != "doublon"){
              echo $line_num." => n'est pas un doublon [".$datas[self::IS_DOUBLON]."]\n";
              continue;
            }

            if(!$revendication->datas->exist($datas[self::IDENTIFIANT])){
              echo $line_num." => l'identifiant n'a pas été trouvé [".$datas[self::IDENTIFIANT]."]\n";
              continue;
            }

            $identifiant = $datas[self::IDENTIFIANT];
            $produitsForIdentifiant = $revendication->datas->$identifiant->produits;
            $produitToRemove = null;
            foreach ($produitsForIdentifiant as $inao => $produitForIdentifiant) {

              $volume = 0;
              foreach ($produitForIdentifiant->volumes as $etat => $saisie) {
                $volume+=$saisie->volume;
              }

              if(!$produitToRemove
                && ($produitForIdentifiant->produit_hash == $datas[self::PRODUIT])
                && (round($volume,2) == round(str_replace(",",".",$datas[self::VOLUME]),2))){
                $produitToRemove = $produitForIdentifiant;
                break;
              }
            }

            if(!$produitToRemove){
              echo $line_num." => le produit pour ".$identifiant." n'a pas été trouvé [".$datas[self::PRODUIT]."]\n";
              continue;
            }

            $hash = $produitForIdentifiant->getHash();
            $key = $produitForIdentifiant->getKey();
            echo $line_num." : SUCCESS ".$hash." on vire la ligne [".implode(",",$datas)."]\n";

            $revendication->datas->get($identifiant)->produits->remove($key);
            if(!count($revendication->datas->get($identifiant)->produits)){
              $revendication->datas->remove($identifiant);
            }
        }
        $revendication->save();
    }


}
