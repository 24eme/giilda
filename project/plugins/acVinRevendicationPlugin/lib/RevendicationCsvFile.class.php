<?php

class RevendicationCsvFile extends CsvFile 
{

  const CSV_COL_TYPE = 0;
  const CSV_COL_UNKNOWN1 = 1;
  const CSV_COL_UNKNOWN2 = 2;
  const CSV_COL_CVI = 3;
  const CSV_COL_RAISON_SOCIALE = 4;
  const CSV_COL_VILLE = 5;
  const CSV_COL_PROPRIO_METAYER = 6;
  const CSV_COL_BAILLEUR = 7;
  const CSV_COL_ADRESSE = 8;
  const CSV_COL_CODE_POSTAL = 9;
  const CSV_COL_COMMUNE = 10;
  const CSV_COL_CODE_PRODUIT = 11;
  const CSV_COL_LIBELLE_PRODUIT = 12;
  const CSV_COL_CAMPAGNE = 13;
  const CSV_COL_VOLUME = 14;
  const CSV_COL_NUMERO_CA = 15;
  const CSV_COL_DATE = 16;
//  const CSV_COL_;

  private function checkLine($line) {
        if (!isset($line[self::CSV_COL_CVI])) {
		$this->errors[] = array('message' => 'La colonne "CVI" est introuvable. Veuillez verifier la cohérence de la ligne', 'num_ligne' => $this->current_line);
		return false;
	}      
	if (!preg_match('/^[0-9]{9}.$/', $line[self::CSV_COL_CVI])) {
		$this->errors[] = array('message' => 'La colonne "CVI" devrait être constituée de nombre', 'num_ligne' => $this->current_line);
		return false;
	}
	if (!$line[self::CSV_COL_RAISON_SOCIALE]) {
		$this->errors[] = array('message' => 'La colonne "raison sociale" ne doit pas être vide', 'num_ligne' => $this->current_line);
                return false;
	}
        if (!$line[self::CSV_COL_VILLE]) {
                $this->errors[] = array('message' => 'La colonne "ville" ne doit pas être vide', 'num_ligne' => $this->current_line);
                return false;
        }
        if (!$line[self::CSV_COL_ADRESSE]) {
                $this->errors[] = array('message' => 'La colonne "adresse" ne doit pas être vide', 'num_ligne' => $this->current_line);
                return false;
        }
	if ($line[self::CSV_COL_PROPRIO_METAYER] != '1' && $line[self::CSV_COL_PROPRIO_METAYER] != '2') {
		$this->errors[] = array('message' => 'Un propriétaire devrait être indiqué comme 1 et un métayer comme 2', 'num_ligne' => $this->current_line);
                return false;
	}
//        if (!$line[self::CSV_COL_COMMUNE]) {
//                $this->errors[] = array('message' => 'La colonne "commune" ne doit pas être vide', 'num_ligne' => $this->current_line);
//                return false;
//        }

        if (!preg_match('/^[0-9]{5}$/', $line[self::CSV_COL_CODE_POSTAL])) {
                $this->errors[] = array('message' => 'Mauvais format pour le code postal', 'num_ligne' => $this->current_line);
                return false;
        }
        if (!$line[self::CSV_COL_CODE_PRODUIT]) {
                $this->errors[] = array('message' => 'La colonne "code produit" ne doit pas être vide', 'num_ligne' => $this->current_line);
                return false;
        }
        if (!$line[self::CSV_COL_LIBELLE_PRODUIT]) {
                $this->errors[] = array('message' => 'La colonne "libellé produit" ne doit pas être vide', 'num_ligne' => $this->current_line);
                return false;
        }

        if (!preg_match('/^[0-9]{4}$/', $line[self::CSV_COL_CAMPAGNE])) {
                $this->errors[] = array('message' => 'Mauvais format pour la campagne', 'num_ligne' => $this->current_line);
                return false;
        }

        if ($line[self::CSV_COL_PROPRIO_METAYER] == "2" && !$line[self::CSV_COL_BAILLEUR]) {
                $this->errors[] = array('message' => 'Un volume en metayage devrait avoir un bailleur', 'num_ligne' => $this->current_line);
                return false;
        }
        if ($line[self::CSV_COL_PROPRIO_METAYER] == "1" && $line[self::CSV_COL_BAILLEUR]) {
                $this->errors[] = array('message' => 'Un bailleur ne peut pas être déclaré sans metayage', 'num_ligne' => $this->current_line);
                return false;
        }
        if ($line[self::CSV_COL_NUMERO_CA]*1 < 0) {
                $this->errors[] = array('message' => 'Le numéro CA doit être un nombre', 'num_ligne' => $this->current_line);
                return false;
        }
        if ($line[self::CSV_COL_CAMPAGNE]*1 < 2000) {
                $this->errors[] = array('message' => 'Le millesime semble erroné', 'num_ligne' => $this->current_line);
                return false;
        }
        
	return true;
  }

  public static function convertTxtToCSV($file) {   
	$r = fopen($file, 'r');
	$w = fopen("$file.tmp", 'w');
	$firstline = 1;
        fwrite($w, "\xef\xbb\xbf"); //UTF8 BOM (pour windows)
	while($s = fgets($r)) {
		if ($firstline && preg_match('/ *;/',substr($s, 10, 5))) {
			return;
		}
		$firstline = 0;
		rtrim($s);
		$s = str_replace(';', ' ', $s);
		$line = utf8_encode(substr(utf8_decode($s), 0, 12)).';'. //CSV_COL_TYPE = 0;
			substr($s, 12, 8).';'. //CSV_COL_UNKNOWN1 = 1
                        substr($s, 20, 8).';'. //CSV_COL_UNKNOWN2
                        substr($s, 28, 10).';'. //CSV_COL_CVI
                        utf8_encode(substr(utf8_decode($s), 38, 30)).';'. //CSV_COL_RAISON_SOCIALE
                        utf8_encode(substr(utf8_decode($s), 68, 30)).';'; //CSV_COL_VILLE
		$s = substr($s, 97);
		$s = preg_replace('/^[^12]*/', '', $s);
		$line .=  
			utf8_encode(substr(utf8_decode($s), 0, 1)).';'. //CSV_COL_PROPRIO_METAYER
                        utf8_encode(substr(utf8_decode($s), 1, 30)).';'. //CSV_COL_BAILLEUR
                        utf8_encode(substr(utf8_decode($s), 31, 90)).';'; //CSV_COL_ADRESSE
		$s = preg_replace('/^.* ([0-9]{5}[^0-9])/', '\1', $s);
		$line .= 
                        substr($s, 0, 5).';'. //CSV_COL_CODE_POSTAL
                        utf8_encode(substr(utf8_decode($s), 5, 30)).';'.//CSV_COL_COMMUNE
                        substr($s, 35, 8).';'.//CSV_COL_CODE_PRODUIT
                        str_replace('/','',str_replace('.','',substr($s, 43, 66))).';';//CSV_COL_LIBELLE_PRODUIT
		$s = substr($s, 99);
		$s = preg_replace('/^[^0-9]*/', '', $s);
		$line .= 
                        substr($s, 0, 4).';'. //CSV_COL_CAMPAGNE
			(preg_replace('/^0*/', '', preg_replace('/ /', '', substr($s, 4, 7)))/100).';'. //CSV_COL_VOLUME
                        preg_replace('/ /', '', substr($s, 11, 9)).';'.//CSV_COL_NUMERO_CA
			substr($s, 20, 8).';'//CSV_COL_DATE
		      ;
		$line = preg_replace('/ *;/', ';', $line);
                $line = preg_replace('/;0*/', ';', $line);
		fwrite($w, "$line\n");
	}
	fclose($w);fclose($r);
	unlink($file);
	rename("$file.tmp", $file);
  }

  public function check() {
	$this->errors = array();
	foreach ($this->getCsv() as $line) {
		 $this->current_line++;
		 $this->checkLine($line);
	}
	return !(count($this->errors));
  } 

  public static function createFromArray($array) {
    $csv = new RevendicationCsvFile();
    $csv->csvdata = $array;
    return $csv;
  }

  public function getErrors() {
    return $this->errors;
  }
}
