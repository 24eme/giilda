<?php 

class MemoCsvFile extends CsvFile 
{
  const CSV_DOSSIER = 0;
  const CSV_CODE_PARTENAIRE = 1;
  const CSV_NUMERO_DE_LIGNE = 2;
  const CSV_TEXTE = 3;
  const CSV_CODE_UTILISATEUR = 4;
  const CSV_DATE_DE_CREATION = 5;
  const CSV_DATE_DE_MODIFICATION = 6;


  public function importMemo() {
    $csvs = $this->getCsv();
    foreach ($csvs as $line) {
      try {
	$soc = SocieteClient::getInstance()->find($line[self::CSV_CODE_PARTENAIRE]);
	if (!$soc) {
	  throw new sfException('la société '.$line[self::CSV_CODE_PARTENAIRE]." n'est pas trouvée");
	}
	if ($line[self::CSV_TEXTE] && !preg_match('/'.preg_replace('/([\/\(\)])/', '\\$1', $line[self::CSV_TEXTE]).'/', $soc->commentaire)) {
	  $soc->commentaire .= $line[self::CSV_TEXTE].' ('.$line[self::CSV_CODE_UTILISATEUR].' ; '.$line[self::CSV_DATE_DE_MODIFICATION].")\n";
	  $soc->save();
	}
      }catch(sfException $e) {
	print "WARNING: ".$e->getMessage()."\n";
      }
    }
  }

  public function getErrors() {
    return $this->errors;
  }

}
