<?php 

class MetayerBailleurCsvFile extends CsvFile 
{
  const CSV_METAYER_CVI = 0;
  const CSV_METAYER_ID = 1;
  const CSV_BAILLEUR_NOM = 2;
  const CSV_BAILLEUR_ID = 3;
  
  public function importMetayerBailleur() {
    $csvs = $this->getCsv();
    foreach ($csvs as $line) {
      try {
	$metayer = EtablissementClient::getInstance()->find($line[self::CSV_METAYER_ID]);
	if (!$metayer) {
	  throw new sfException($line[self::CSV_METAYER_ID]. ': Metayer not found');
	}
	$bailleur = EtablissementClient::getInstance()->find($line[self::CSV_BAILLEUR_ID]);
	if (!$bailleur) {
	  throw new sfException($line[self::CSV_BAILLEUR_ID]. ': Metayer not found');
	}
	$liaison = $metayer->addLiaison(EtablissementClient::TYPE_LIAISON_BAILLEUR, $bailleur);
	$liaison->add('aliases')->add($line[self::CSV_BAILLEUR_NOM], $line[self::CSV_BAILLEUR_NOM]);
	$bailleur->addLiaison(EtablissementClient::TYPE_LIAISON_METAYER, $metayer);

	if ($metayer->_id == $bailleur->_id) {
	  throw new sfException('metayer et bailleur ne devraient pas être identifiques ('.$metayer->_id.')');
	}
	$metayer->save();
	$bailleur->save();
      }catch(sfException $e) {
	print "WARNING: ".$e->getMessage()."\n";
      }
    }
  }

  public function getErrors() {
    return $this->errors;
  }

}
