<?php

class importVracSV12Task extends importVracTask
{

  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_CODE_VITICULTEUR = 11;
  const CSV_CODE_CHAI = 12;
  const CSV_NUMERO_DECLARATION = 2;
  const CSV_NUMERO_LIGNE = 3;
  const CSV_CODE_APPELLATION = 4;
  const CSV_VOLUME_LIBRE = 5;
  const CSV_VOLUME_BLOQUE = 6;
  const CSV_DATE_CREATION = 13;
  const CSV_DATE_MODIFICATION = 14;

  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
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

  }

  public function importSV12($lines) {
    $sv12 = null;

    foreach($lines as $i => $line) {
      $vrac = null;
      try{
        $vrac = $this->importVrac($line);
      } catch (Exception $e) {
        $this->log(sprintf("[VRAC]%s (ligne %s) : %s", $e->getMessage(), $i, implode($line, ";")));
        
        continue;
      }
      $vrac->save();

      if ($vrac->campagne == '2012-2013') {
        
        continue;
      }

      try{
        $sv12 = $this->importLine($sv12, $vrac);
      } catch (Exception $e) {
        $this->log(sprintf("[SV12]%s (ligne %s) : %s", $e->getMessage(), $i, implode($line, ";")));

        return;
      }
    }
    
    if(!$sv12) {
      return;
    }

    $sv12->validate(array('pas_solder' => true));
    $sv12->facturerMouvements();
    $sv12->save();
  }

  public function importLine($sv12, $vrac) {
    if(is_null($sv12)) {
      $sv12 = SV12Client::getInstance()->createOrFind($vrac->acheteur_identifiant, SV12Client::getInstance()->buildPeriodeFromCampagne($vrac->campagne));
    }

    if (!in_array($vrac->type_transaction, VracClient::$types_transaction_non_vins)) {

      return $sv12;
    }

    if($vrac->valide->statut != VracClient::STATUS_CONTRAT_SOLDE) {
        
        return $sv12;
    }

    $contrat = $sv12->addContrat($vrac);
    $contrat->volume = $vrac->volume_enleve;

    return $sv12;
  }

  protected function getId($line) {
	
	  return SV12Client::getInstance()->buildId($this->getIdentifiantAcheteur($line), $this->getPeriode($line)); 
  }
  
  protected function getPeriode($line) {
    
    return SV12Client::getInstance()->buildPeriode($this->getDateCampagne($line)->format('Y-m-d'));
  }
}
