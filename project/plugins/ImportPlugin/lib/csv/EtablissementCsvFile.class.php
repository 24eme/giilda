<?php 

class EtablissementCsvFile extends CsvFile 
{

  const CSV_ID = 0;
  const CSV_ID_SOCIETE = 2;
  const CSV_STATUT = 2;
  const CSV_NOM = 2;
  const CSV_REGION = 5;
  const CSV_NO_ACCISES = 6;
  const CSV_COMMENTAIRE = 7;
  const CSV_SITE_FICHE = 8;
  const CSV_CVI = 9;
  const CSV_RECETTE_LOCALE = 11;
  const CSV_CARTE_PRO = 13;
  const CSVPAR_COMMUNE = 14;
  const CSVPAR_CODE_PAYS = 15;
  const CSVPAR_DATE_CREATION = 16;
  const CSVPAR_DATE_MODIFICATION = 17;
  const CSVPAR_RELANCE_DS = 18;
  const CSVPAR_EN_VALDELOIRE = 19;
  const CSVPAR_ENSEIGNE = 20;
  const CSVPAR_REGION_VITI = 21;
  const CSV_TYPE_PARTENAIRE_VITICULTEUR = 'V';
  const CSV_TYPE_PARTENAIRE_NEGOCE = 'N';
  const CSV_TYPE_PARTENAIRE_COURTIER = 'C';

  const CSVCOURTIER_NUMCARTE = 25;
  const CSVCOURTIER_ISCOURTIER = 26;
  const CSVCOURTIER_ISCOURTIER_VALEUR = 'COURTIER';

  const CSVCAV_DOSSIER = 24;
  const CSVCAV_CODE_CHAI = 25;
  const CSVCAV_CVI = 27;
  const CSVCAV_ADRESSE1 = 28;
  const CSVCAV_ADRESSE2 = 29;
  const CSVCAV_ADRESSE3 = 30;
  const CSVCAV_ADRESSE4 = 31;
  const CSVCAV_CODE_POSTAL = 32;
  const CSVCAV_PAYS = 33;
  const CSVCAV_CODE_DEPARTEMENT = 34;
  const CSVCAV_CODE_COMMUNE = 35;
  const CSVCAV_LIBELLE_COMMUNE = 36;
  const CSVCAV_DRA = 40;
  const CSVCAV_EXCLUS_RELANCE_DRM = 41;

  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]+/', $line[self::CSVPAR_CODE_CLIENT])) {

      throw new Exception(sprintf('Numero de dossier invalide : %s', $line[self::CSVPAR_CODE_CLIENT]));
    }
  }

  public function importEtablissements() {
    $this->errors = array();
    $etablissements = array();
    $csvs = $this->getCsv();
      foreach ($csvs as $line) {
        try {
      	  $this->verifyCsvLine($line);

          $famille = $this->convertTypeInFamille($line[self::CSVPAR_TYPE_PARTENAIRE]);
          if (!$famille) {
	          echo "Etablissement ERROR: ".$line[self::CSVPAR_CODE_CLIENT].": Pas de Famille connue\n";
            continue;
          }

      	  $e = EtablissementClient::getInstance()->find($line[self::CSVPAR_CODE_CLIENT], acCouchdbClient::HYDRATE_JSON);
          
          if ($e) {
	          echo "WARNING: Etablissement ".$line[self::CSVPAR_CODE_CLIENT]." existe\n";
	          continue;
          }
	
          $id = sprintf("%06d", $line[self::CSVPAR_CODE_CLIENT]).sprintf("%02d", $chai);
  	       $e = EtablissementClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
          if ($e) {
        	  echo "WARNING: Etablissement ".$id." existe\n";
        	  continue;
          }

        	$e = new Etablissement();
          $e->identifiant = $id;

        	if (isset($line[self::CSVCAV_LIBELLE_COMMUNE])) {
        	        $e->nom = $line[self::CSVPAR_NOM_DU_PARTENAIRE].' - '.$line[self::CSVCAV_LIBELLE_COMMUNE];
        	}else{
        		$e->nom = $line[self::CSVPAR_NOM_DU_PARTENAIRE];
        	}
        	$e->raison_sociale = $line[self::CSVPAR_NOM_DU_PARTENAIRE];
                $e->cvi = (isset($line[self::CSVCAV_CVI]) && $line[self::CSVPAR_TYPE_PARTENAIRE] != self::CSV_TYPE_PARTENAIRE_COURTIER)? $line[self::CSVCAV_CVI] : null;
        	if (isset( $line[self::CSVCAV_LIBELLE_COMMUNE])) {
        	        $e->siege->commune = $line[self::CSVCAV_LIBELLE_COMMUNE];
                	$e->siege->code_postal = $line[self::CSVCAV_CODE_POSTAL];
        		if (!preg_match('/^(bailleur|métayage)/i', $line[self::CSVCAV_ADRESSE1])) {
        		    $e->siege->adresse = preg_replace('/,/', '', $line[self::CSVCAV_ADRESSE1]);
        		    if(preg_match('/[a-z]/i', $line[self::CSVCAV_ADRESSE2])) {
        		      $e->siege->add('adresse_complementaire',preg_replace('/,/', '', $line[self::CSVCAV_ADRESSE2]));
        		      if(preg_match('/[a-z]/i', $line[self::CSVCAV_ADRESSE3])) {
        			$e->siege->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSVCAV_ADRESSE3]);
        			if(preg_match('/[a-z]/i', $line[self::CSVCAV_ADRESSE4])) {
        			  $e->siege->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSVCAV_ADRESSE4]);
        			}}}
        		}
        	}else{
        		$e->siege->commune = $line[self::CSVPAR_COMMUNE];
                        $e->siege->code_postal = $line[self::CSVPAR_CODE_POSTAL];
        	}
        	if (!$e->siege->adresse) {
        		$e->siege->adresse = preg_replace('/,/', '', $line[self::CSVPAR_ADRESSE1]);
        	        if(preg_match('/[a-z]/i', $line[self::CSVPAR_ADRESSE2])) {
                        $e->siege->add('adresse_complementaire',preg_replace('/,/', '', $line[self::CSVPAR_ADRESSE2]));
        	        if(preg_match('/[a-z]/i', $line[self::CSVPAR_ADRESSE3])) {
                        $e->siege->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSVPAR_ADRESSE3]);
                	if(preg_match('/[a-z]/i', $line[self::CSVPAR_ADRESSE4])) {
                        $e->siege->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSVPAR_ADRESSE4]);
        	        }}}
        	}
                $e->famille = $famille;
                $e->sous_famille = $this->getSousFamilleDefaut($famille);
                $e->interpro = 'INTERPRO-inter-loire';
              
        	if (($e->isViticulteur() || $e->isNegociant()) && $line[self::CSVPAR_RELANCE_DS] == 'N') {
        	  $e->relance_ds = EtablissementClient::RELANCE_DS_NON;
        	}elseif($e->isViticulteur() || $e->isNegociant()){
        	  $e->relance_ds = EtablissementClient::RELANCE_DS_OUI;
        	}

        	//le champ en activité contient en réalisé la valeur de suspendu 
        	if ($line[self::CSVPAR_EN_ACTIVITE] == 'O') {
        		$e->statut = EtablissementClient::STATUT_SUSPENDU;
                }else{
        		$e->statut = EtablissementClient::STATUT_ACTIF;
                }
        	$e->id_societe = "SOCIETE-".sprintf("%06d", $line[self::CSVPAR_CODE_CLIENT]); 

        	if ($line[self::CSVPAR_EN_VALDELOIRE] == 'N') {
        		$e->region = EtablissementClient::REGION_HORSINTERLOIRE;
        	}else if ($line[self::CSVPAR_REGION_VITI] == 'T') {
        		$e->region = EtablissementClient::REGION_TOURS;
        	}else if ($line[self::CSVPAR_REGION_VITI] == 'N') {
        		$e->region = EtablissementClient::REGION_NANTES;
                }else if ($line[self::CSVPAR_REGION_VITI] == 'A') {
        		$e->region = EtablissementClient::REGION_ANGERS;
        	}else{
        		$e->region = EtablissementClient::REGION_HORSINTERLOIRE;
        	}
                
          if ($e->isViticulteur() && isset($line[self::CSVCAV_DRA]) && $line[self::CSVCAV_DRA] == 'OUI') {
              $e->type_dr = EtablissementClient::TYPE_DR_DRA;
          }elseif($e->isViticulteur()) {
              $e->type_dr = EtablissementClient::TYPE_DR_DRM;
          }

          if ($e->isViticulteur() && isset($line[self::CSVCAV_EXCLUS_RELANCE_DRM]) && $line[self::CSVCAV_EXCLUS_RELANCE_DRM] == 'O') {
            $e->exclusion_drm = EtablissementClient::EXCLUSION_DRM_OUI;
          } elseif($e->isViticulteur()) {
            $e->exclusion_drm = EtablissementClient::EXCLUSION_DRM_NON;
          }
                
        	if ($line[self::CSVPAR_CODE_PARTENAIRE_RECETTE_LOCALE]*1)
        	        $e->recette_locale->id_douane = "SOCIETE-".sprintf("%06d", $line[self::CSVPAR_CODE_PARTENAIRE_RECETTE_LOCALE]);

        	if ($line[self::CSVPAR_TYPE_PARTENAIRE] == self::CSV_TYPE_PARTENAIRE_COURTIER && isset($line[self::CSVCOURTIER_NUMCARTE])) {
        		$e->carte_pro = $line[self::CSVCOURTIER_NUMCARTE];
        	}
        	$e->save();
      
      }catch(Exception $e) {
        echo $e->getMessage()."\n";
      }
    }
    return $etablissements;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function convertTypeInFamille($type) {

    $types_familles = array(
      self::CSV_TYPE_PARTENAIRE_VITICULTEUR => EtablissementFamilles::FAMILLE_PRODUCTEUR,
      self::CSV_TYPE_PARTENAIRE_NEGOCE => EtablissementFamilles::FAMILLE_NEGOCIANT,
      self::CSV_TYPE_PARTENAIRE_COURTIER => EtablissementFamilles::FAMILLE_COURTIER,
    );

    if (array_key_exists($type, $types_familles)) {
      
      return $types_familles[$type];
    }

    return null;
  }

  public function getSousFamilleDefaut($famille) {
    if($famille == EtablissementFamilles::FAMILLE_PRODUCTEUR) {

        return EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
    }

    if($famille == EtablissementFamilles::FAMILLE_NEGOCIANT) {
        
        return EtablissementFamilles::SOUS_FAMILLE_REGIONAL;
    }

    if($famille == EtablissementFamilles::FAMILLE_COURTIER) {
        
        return '';
    }
  }
}
