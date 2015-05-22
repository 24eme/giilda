<?php 

class SocieteCsvFile extends CsvFile 
{
  const CSV_PARTENAIRE_DOSSIER = 0;
  const CSV_PARTENAIRE_CODE = 1;
  const CSV_PARTENAIRE_NOM = 2;
  const CSV_PARTENAIRE_NOM_REDUIT = 3;
  const CSV_PARTENAIRE_TYPE = 4;
  const CSV_PARTENAIRE_COOPGROUP = 5;
  const CSV_PARTENAIRE_COURTIER = 6;
  const CSV_PARTENAIRE_RECETTE_LOCALE = 7;
  const CSV_PARTENAIRE_ENACTIVITE = 8;
  const CSV_PARTENAIRE_ADRESSE1 = 9;
  const CSV_PARTENAIRE_ADRESSE2 = 10;
  const CSV_PARTENAIRE_ADRESSE3 = 11;
  const CSV_PARTENAIRE_ADRESSE4 = 12;
  const CSV_PARTENAIRE_CODEPOSTAL = 13;
  const CSV_PARTENAIRE_COMMUNE = 14;
  const CSV_PARTENAIRE_PAYS = 15;
  const CSV_PARTENAIRE_CREATION_DATE = 16;
  const CSV_PARTENAIRE_MODIFICATION_DATE = 17;
  const CSV_PARTENAIRE_RELANCE_STOCK = 18;
  const CSV_PARTENAIRE_JOURNAL_ABONNE = 19;
  const CSV_PARTENAIRE_JOURNAL_NBEXEMPLAIRES = 20;
  const CSV_PARTENAIRE_EXPLOITANTBAILLEUR = 21;
  const CSV_PARTENAIRE_CHAIPRINICPAL = 22;
  const CSV_PARTENAIRE_ENSEIGNE = 23;
  const CSV_PARTENAIRE_CODE_FOURNISSEUR = 24;
  const CSV_PARTENAIRE_TYPE_FOURNISSEUR = 25;


  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]+/', $line[self::CSV_PARTENAIRE_CODE])) {

      throw new Exception(sprintf('Numero de dossier invalide : %s', $line[self::CSV_PARTENAIRE_CODE]));
    }
  }

  public function importSocietes () {
    $this->errors = array();
    $societes = array();
    $csvs = $this->getCsv();
    try {
      foreach ($csvs as $line) {
      	$this->verifyCsvLine($line);

      	$s = SocieteClient::getInstance()->find($line[self::CSV_PARTENAIRE_CODE], acCouchdbClient::HYDRATE_JSON);
        if ($s) {
	  echo "ERROR: Societe exists (".$line[self::CSV_PARTENAIRE_CODE].")\n";
	  continue;
          acCouchdbManager::getClient()->deleteDoc($s);
        }

      	$s = new Societe();
        $s->identifiant = $line[self::CSV_PARTENAIRE_CODE];
        $s->constructId();
        $s->raison_sociale = $line[self::CSV_PARTENAIRE_NOM];
	$s->raison_sociale_abregee = $line[self::CSV_PARTENAIRE_NOM_REDUIT];
	$s->siege->adresse = preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE1]);
        if(preg_match('/[a-z]/i', $line[self::CSV_PARTENAIRE_ADRESSE2])) {
        $s->siege->add('adresse_complementaire',preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE2]));
        if(preg_match('/[a-z]/i', $line[self::CSV_PARTENAIRE_ADRESSE3])) {
        $s->siege->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE3]);
        if(preg_match('/[a-z]/i', $line[self::CSV_PARTENAIRE_ADRESSE4])) {
        $s->siege->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSV_PARTENAIRE_ADRESSE4]);
        }}}
        $s->siege->code_postal = $line[self::CSV_PARTENAIRE_CODEPOSTAL];
        $s->siege->commune = $line[self::CSV_PARTENAIRE_COMMUNE];
        $s->siege->add('pays', $this->convertCountry($line[self::CSV_PARTENAIRE_PAYS]));
	$s->interpro = 'INTERPRO-inter-loire';
        if ($line[self::CSV_PARTENAIRE_COOPGROUP] == 'C') {
		$s->cooperative = 1;
        }
	//Incohérent mais ce champ signifie en réalisé suspendu
        if ($line[self::CSV_PARTENAIRE_ENACTIVITE] == 'O') {
                $s->statut = SocieteClient::STATUT_SUSPENDU;
        }else{
                $s->statut = SocieteClient::STATUT_ACTIF;
        }
	if ($line[self::CSV_PARTENAIRE_TYPE] == 'N') {
		$s->type_societe = SocieteClient::SUB_TYPE_NEGOCIANT;
		$s->code_comptable_client = sprintf("04%06d", $line[self::CSV_PARTENAIRE_CODE]);
        }else if ($line[self::CSV_PARTENAIRE_TYPE] == 'C') {
		$s->type_societe = SocieteClient::SUB_TYPE_COURTIER;
	}else if ($line[self::CSV_PARTENAIRE_TYPE] == 'V') {
		$s->type_societe = SocieteClient::SUB_TYPE_VITICULTEUR;
		$s->code_comptable_client = sprintf("02%06d", $line[self::CSV_PARTENAIRE_CODE]);
	}else if ($line[self::CSV_PARTENAIRE_TYPE] == 'R') {
                $s->type_societe = SocieteClient::SUB_TYPE_DOUANE;
	}else if ($line[self::CSV_PARTENAIRE_TYPE] == 'D') {
                $s->type_societe = SocieteClient::TYPE_PRESSE;
	}else if ($line[self::CSV_PARTENAIRE_TYPE] == 'A') {
                $s->type_societe = SocieteClient::SUB_TYPE_AUTRE;
  } else{
           $t = $line[self::CSV_PARTENAIRE_TYPE];
              $s->type_societe = SocieteClient::SUB_TYPE_AUTRE;
            //throw new sfException("type partenaire inconnu => type : $t ");
	}
	if ($line[self::CSV_PARTENAIRE_ENSEIGNE])
		$s->enseignes->add(null, $line[self::CSV_PARTENAIRE_ENSEIGNE]);
        if($line[self::CSV_PARTENAIRE_CODE_FOURNISSEUR]){
                $s->code_comptable_fournisseur = sprintf('%08d', $line[self::CSV_PARTENAIRE_CODE_FOURNISSEUR]);                
        }
        $s->add('type_fournisseur',array());
        if($line[self::CSV_PARTENAIRE_TYPE_FOURNISSEUR]){
                $fournisseur_tag = preg_replace ('/([A-Za-z ]*)(MDV|PLV)/','$2',$line[self::CSV_PARTENAIRE_TYPE_FOURNISSEUR]);
                $s->add('type_fournisseur',array($fournisseur_tag));
        }
      	$s->save();
      }
    }catch(Execption $e) {
      $this->error[] = $e->getMessage();
    }
    return $societes;
  }

  public function getErrors() {
    return $this->errors;
  }
  
   protected function convertCountry($country) {
    $countries = ConfigurationClient::getInstance()->getCountryList();

    if($country == 'FRA') {
      $country = 'FR';
    }

    if($country == 'TU') {
      $country = 'TR';
    }

    if(!array_key_exists($country, $countries)) {
      
      throw new sfException(sprintf("Code pays '%s' invalide", $country));
    }
    
    return $country;
  }



}
