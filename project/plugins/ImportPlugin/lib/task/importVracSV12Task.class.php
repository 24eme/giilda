<?php

class importVracSV12Task extends importVracTask
{

  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'vrac-sv12';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importVrac|INFO] task does things.
Call it with:

  [php symfony importEtablissement|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $context = sfContext::createInstance($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    set_time_limit(0);
    $i = 1;
    $id = null;
    $lines = array();
    foreach(file($arguments['file']) as $line) {
      $data = str_getcsv($line, ';');
	
      $id_line = $this->getId($data);

      if($id && $id != $id_line) {
        $this->importSV12($lines);
        $lines = array();
      }
      
      $id = $id_line;
      $lines[$i] = $data;
      $i++;
    }

    if(count($lines) > 0) {
      $this->importSV12($lines);
    }
  }

  public function importSV12($lines) {
    $sv12 = null;

    foreach($lines as $i => $line) {
      $vrac = null;
      try{
        $vrac = $this->importVrac($line);
      } catch (Exception $e) {
        $this->log(sprintf("[VRAC]ERROR;%s (ligne %s) : %s", $e->getMessage(), $i, implode($line, ";")));
        
        continue;
      }
      $vrac->save();

      try{
        $sv12 = $this->importLine($sv12, $vrac, $line);
      } catch (Exception $e) {
        $this->log(sprintf("[SV12]ERROR;%s (ligne %s) : %s", $e->getMessage(), $i, implode($line, ";")));

        return;
      }
    }
    
    if(!$sv12) {
      return;
    }

    $sv12->valide->date_saisie = $sv12->getDate();

    $sv12->validate(array('pas_solder' => true));

    if ($sv12->campagne == ConfigurationClient::getInstance()->buildCampagne(date('Y-m-d'))) { 
        $sv12->valide->statut = SV12Client::STATUT_VALIDE_PARTIEL;
    }

    $sv12->facturerMouvements();
    $sv12->save();
  }

  public function importLine($sv12, $vrac, $line) {
    if (!in_array($vrac->type_transaction, VracClient::$types_transaction_non_vins)) {

      return $sv12;
    }

    if(is_null($sv12)) {
      $sv12 = SV12Client::getInstance()->createOrFind($vrac->acheteur_identifiant, SV12Client::getInstance()->buildPeriodeFromCampagne($vrac->campagne));
    }

    if($vrac->valide->statut != VracClient::STATUS_CONTRAT_SOLDE) {
        
        return $sv12;
    }

    $contrat = $sv12->addContrat($vrac);
    $contrat->volume = $vrac->volume_enleve;
    $contrat->cvo = $this->convertToFloat($line[self::CSV_TAUX_CVO_GLOBAL]);

    return $sv12;
  }

  protected function getId($line) {
	
	  return SV12Client::getInstance()->buildId($this->getIdentifiantAcheteur($line), $this->getPeriode($line)); 
  }
  
  protected function getPeriode($line) {
    
    return SV12Client::getInstance()->buildPeriode($this->getDateCampagne($line)->format('Y-m-d'));
  }
}
