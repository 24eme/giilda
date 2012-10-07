<?php

class RevendicationCsvFile extends CsvFile 
{

  public static function convertTxtToCSV($file) {
	$r = fopen($file, 'r');
	$w = fopen("$file.tmp", 'w');
	while($s = fgets($r)) {
		rtrim($s);
		$s = str_replace(';', ' ', $s);
		fwrite($w, substr($s, 0, 12).';'.
			substr($s, 12, 8).';'.
                        substr($s, 20, 8).';'.
                        substr($s, 28, 10).';'.
                        substr($s, 38, 30).';'.
                        substr($s, 68, 30).';');
		$s = substr($s, 97);
		$s = preg_replace('/^[^12]*/', '', $s);
		fwrite($w, 
			substr($s, 0, 1).';'.
                        substr($s, 1, 30).';'.
                        substr($s, 31, 90).';');
		$s = preg_replace('/^.* ([0-9]{5}[^0-9])/', '\1', $s);
		fwrite($w, 
                        substr($s, 0, 5).';'.
                        substr($s, 5, 30).';'.
                        substr($s, 35, 8).';'.
                        substr($s, 43, 66).';');
		$s = substr($s, 99);
		$s = preg_replace('/^[^0-9]*/', '', $s);
		fwrite($w,
                        substr($s, 0, 13).';'.
                        substr($s, 13, 7).';'.
			substr($s, 20, 8).';'
		      );
		fwrite($w, "\n");
	}
	fclose($w);fclose($r);
	unlink($file);
	rename("$file.tmp", $file);
  }


  const NOEUD_TEMPORAIRE = 'TMP';
  const DEFAULT_KEY = 'DEFAUT';
  const CSV_COL_DETAIL_ID_ETABLISSEMENT_INTERNE = 64;

  public static function createFromArray($array) {
    $csv = new RevendicationCsvFile();
    $csv->csvdata = $array;
    return $csv;
  }

  public function getErrors() {
    return $this->errors;
  }
}
