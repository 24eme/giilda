<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class updateTypeVracTask
 * @author mathurin
 */
class updatePrixVracTask extends importAbstractTask
{

  const LOG_ID_VRAC = 0;

  protected $error_term = "\033[31mERREUR:\033[0m";
  protected $warning_term = "\033[33m----->ATTENTION:\033[0m ";

  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'update';
    $this->name             = 'prix-vrac';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateTypeVrac|INFO] task does things.
Call it with:

  [php symfony update:type-vrac|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    set_time_limit(0);
    $i = 1;
    foreach(file($arguments['file']) as $line) {
      try{
        $vrac = $this->updatePrixVrac(trim($line));
	 if($vrac !== null){
                echo " Save du vrac ".$vrac->_id." \n";
	        $vrac->save();
	 }
      } catch (Exception $e) {
        $this->logLigne($this->error_term, $e->getMessage(), $data);

        continue;
      }

      $i++;
    }

  }

  public function updatePrixRaisin($id_vrac) {

        $id = (preg_match('/^VRAC-/', $id_vrac))? $id_vrac : 'VRAC-'.$id_vrac;
        $v = VracClient::getInstance()->find($id);
        
        if (!$v) {
            echo $this->error_term." -> Le contrat ".$id_vrac." n'existe pas en base, cela est curieux! \n";
	return null;
        }else{
            echo "On ne traite pas le contrat numéro ".$id_vrac." \n";
            if($v->type_transaction != VracClient::TYPE_TRANSACTION_RAISINS){
                return null;
            }

//            if($v->prix_unitaire > 20){
    //          $old_prix = $v->prix_unitaire;
//              $v->prix_unitaire *= $v->bouteilles_contenance_volume;
//              $v->prix_unitaire = $this->convertToFloat($v->prix_unitaire);
//              echo $this->warning_term." le prix unitaire a changé : ".$old_prix." => ".$v->prix_unitaire."  (ANORMAL)";
//            }
//
//              $v->prix_initial_unitaire = $this->convertToFloat($v->prix_unitaire);
//              $v->bouteilles_quantite = (int) ($v->volume_propose / $v->bouteilles_contenance_volume);              
//              $v->prix_total = $this->convertToFloat($v->bouteilles_quantite *  $v->prix_unitaire);
//              
//              $v->prix_initial_unitaire_hl = $this->convertToFloat($v->prix_total / $v->volume_propose);
//              $v->prix_unitaire_hl = $this->convertToFloat($v->prix_initial_unitaire_hl);
//              $v->prix_initial_total = $this->convertToFloat($v->prix_total);
//              
//              echo " ===> contrat ".$id_vrac." MAJ des prix ini_hl total...\n";
              return $v;
            }	
        return null;
    }
  
  public function updatePrixVrac($id_vrac) {

        $id = (preg_match('/^VRAC-/', $id_vrac))? $id_vrac : 'VRAC-'.$id_vrac;
        $v = VracClient::getInstance()->find($id);
        
        if (!$v) {
            echo $this->error_term." -> Le contrat ".$id_vrac." n'existe pas en base, cela est curieux! \n";
	return null;
        }else{
            echo " Traitement du contrat numéro ".$id_vrac." \n";
            if($v->type_transaction != VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE) {
                $v->update();
                echo " ===> contrat ".$id_vrac." MAJ des prix ini_hl total...\n";
                return $v;
//                echo $this->warning_term." le contrat numéro ".$id_vrac." n'est plus BOUTEILLE! \n";
//                return null;
            }
            $v->bouteilles_contenance_volume = 0.0075;
            $v->bouteilles_contenance_libelle = "75 cl";

            if($v->prix_unitaire > 20){
            $old_prix = $v->prix_unitaire;
              $v->prix_unitaire *= $v->bouteilles_contenance_volume;
              $v->prix_unitaire = $this->convertToFloat($v->prix_unitaire);
              echo $this->warning_term." le prix unitaire a changé : ".$old_prix." => ".$v->prix_unitaire."  (ANORMAL)";
            }

              $v->prix_initial_unitaire = $this->convertToFloat($v->prix_unitaire);
              $v->bouteilles_quantite = (int) ($v->volume_propose / $v->bouteilles_contenance_volume);              
              $v->prix_total = $this->convertToFloat($v->bouteilles_quantite *  $v->prix_unitaire);
              
              $v->prix_initial_unitaire_hl = $this->convertToFloat($v->prix_total / $v->volume_propose);
              $v->prix_unitaire_hl = $this->convertToFloat($v->prix_initial_unitaire_hl);
              $v->prix_initial_total = $this->convertToFloat($v->prix_total);
              
              echo " ===> contrat ".$id_vrac." MAJ des prix ini_hl total...\n";
              return $v;
            }	
        return null;
    }
  }
