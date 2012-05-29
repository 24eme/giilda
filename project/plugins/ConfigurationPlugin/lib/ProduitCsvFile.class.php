<?php

class ProduitCsvFile extends CsvFile 
{
  const CSV_PRODUIT_INTERPRO = 0;
  const CSV_PRODUIT_CATEGORIE_LIBELLE = 1;    //CATEGORIE == CERTIFICATION
  const CSV_PRODUIT_CATEGORIE_CODE = 2;       //CATEGORIE == CERTIFICATION
  const CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT = 'C';
  const CSV_PRODUIT_GENRE_LIBELLE = 3;
  const CSV_PRODUIT_GENRE_CODE = 4;
  const CSV_PRODUIT_GENRE_CODE_APPLICATIF_DROIT = 'G';
  const CSV_PRODUIT_DENOMINATION_LIBELLE = 5; //DENOMINATION == APPELLATION
  const CSV_PRODUIT_DENOMINATION_CODE = 6;    //DENOMINATION == APPELLATION
  const CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT = 'A';
  const CSV_PRODUIT_MENTION_LIBELLE = 7;
  const CSV_PRODUIT_MENTION_CODE = 8;
  const CSV_PRODUIT_LIEU_LIBELLE = 9;
  const CSV_PRODUIT_LIEU_CODE = 10;
  const CSV_PRODUIT_COULEUR_LIBELLE = 11;
  const CSV_PRODUIT_COULEUR_CODE = 12;
  const CSV_PRODUIT_CEPAGE_LIBELLE = 13;
  const CSV_PRODUIT_CEPAGE_CODE = 14;
  /*
  const CSV_PRODUIT_MILLESIME_LIBELLE = 15;
  const CSV_PRODUIT_MILLESIME_CODE = 16;
  const CSV_PRODUIT_DEPARTEMENTS = 17;
  const CSV_PRODUIT_DOUANE_CODE = 18;
  const CSV_PRODUIT_DOUANE_TAXE = 19;
  const CSV_PRODUIT_DOUANE_DATE = 20;
  const CSV_PRODUIT_DOUANE_NOEUD = 21;
  const CSV_PRODUIT_CVO_TAXE = 22;
  const CSV_PRODUIT_CVO_DATE = 23;
  const CSV_PRODUIT_CVO_NOEUD = 24;
  const CSV_PRODUIT_REPLI_ENTREE = 25;
  const CSV_PRODUIT_REPLI_SORTI = 26;
  const CSV_PRODUIT_DECLASSEMENT_ENTREE = 27;
  const CSV_PRODUIT_DECLASSEMENT_SORTI = 28;
  */
  
  protected $config;
  protected $errors;
  
  public function __construct($config, $file) {
    parent::__construct($file);
    $this->config = $config;
  }

  private function getProduit($line) 
  {
    $hash  = 'certifications/'.$this->getKey($line[self::CSV_PRODUIT_CATEGORIE_CODE]);
    $hash .= '/genres/'.$this->getKey($line[self::CSV_PRODUIT_GENRE_CODE], true);
    $hash .= '/appellations/'.$this->getKey($line[self::CSV_PRODUIT_DENOMINATION_CODE], true);
    $hash .= '/mentions/'.$this->getKey($line[self::CSV_PRODUIT_MENTION_CODE], true);
    $hash .= '/lieux/'.$this->getKey($line[self::CSV_PRODUIT_LIEU_CODE], true);
    $hash .= '/couleurs/'.strtolower($this->couleurKeyToCode($line[self::CSV_PRODUIT_COULEUR_CODE]));
    $hash .= '/cepages/'.$this->getKey($line[self::CSV_PRODUIT_CEPAGE_CODE], true);
    return $this->config->declaration->getOrAdd($hash);
  }
  
  private function couleurKeyToCode($key) {
    $correspondances = array(1 => "rouge",
                             2 => "rose",
                             3 => "blanc");

    if (!isset($correspondances[$key])) {
      throw new Exception("Couleur pas connue $key");
    }
    return $correspondances[$key];
  }
  
  private function getKey($key, $withDefault = false) 
  {
    if ($withDefault) {
      return ($key)? $key : Configuration::DEFAULT_KEY;
    } 
    if (!$key) {
      throw new Exception('La clé "'.$key.'" n\'est pas valide');
    }
    return $key;
  }

  public function importProduits() 
  {    
    $this->errors = array();
    $csv = $this->getCsv();
    try {
      foreach ($csv as $line) {
	$produit = $this->getProduit($line);
	$produit->setDonneesCsv($line);
      }
    } catch(Execption $e) {
    	$this->errors[] = $e->getMessage();
    }
    return $this->config;
  }

  public function getErrors() 
  {
    return $this->errors;
  }
}