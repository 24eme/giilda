<?php 

class VracCsvFile extends CsvFile 
{
  const CSV_DOSSIER = 0;
  const CSV_CAMPAGNE = 1;
  const CSV_NUMERO_CONTRAT = 2;
  const CSV_DATE_ENREGISTREMENT = 3;
  const CSV_CODE_RECETTE_LOCALE = 4;
  const CSV_CODE_VITICULTEUR = 5;
  const CSV_CODE_CHAI_CAVE = 6;
  const CSV_CODE_NEGOCIANT = 7;
  const CSV_CODE_COURTIER = 8;
  const CSV_CODE_APPELLATION = 9;
  const CSV_TYPE_PRODUIT = 10;
  const CSV_MILLESIME = 11;
  const CSV_COTISATION_CVO_NEGOCIANT = 12;
  const CSV_COTISATION_CVO_VITICULTEUR = 13;
  const CSV_VOLUME = 14;
  const CSV_UNITE_VOLUME = 15;
  const CSV_COEF_CONVERSION_VOLUME = 16;
  const CSV_MODE_CONVERSION_VOLUME = 17;
  const CSV_VOLUME_PROPOSE_HL = 18;
  const CSV_VOLUME_ENLEVE_HL = 19;
  const CSV_PRIX_VENTE = 20;
  const CSV_CODE_DEVISE = 21;
  const CSV_UNITE_PRIX_VENTE = 22;
  const CSV_COEF_CONVERSION_PRIX = 23;
  const CSV_MODE_CONVERSION_PRIX = 24;
  const CSV_PRIX_AU_LITRE = 25;
  const CSV_CONTRAT_SOLDE = 26;
  const CSV_DATE_SIGNATURE_OU_CREATION = 27;
  const CSV_DATE_DERNIERE_MODIFICATION = 28;
  const CSV_CODE_SAISIE = 29;
  const CSV_DATE_LIVRAISON = 30;
  const CSV_CODE_MODE_PAIEMENT = 31;
  const CSV_COMPOSTAGE = 32;
  const CSV_TYPE_CONTRAT = 33;
  const CSV_ATTENTE_ORIGINAL = 34;
  const CSV_CATEGORIE_VIN = 35;
  const CSV_CEPAGE = 36;
  const CSV_MILLESIME_ANNEE = 37;
  const CSV_PRIX_HORS_CVO = 38;
  const CSV_PRIX_CVO_INCLUSE = 39;
  const CSV_TAUX_CVO_GLOBAL = 40;

  const CSV_CIAPL_DOSSIER = 41;
  const CSV_CIAPL_CAMPAGNE = 42;
  const CSV_CIAPL_CODE = 43;
  const CSV_CIAPL_LIBELLE = 44;
  const CSV_CIAPL_CODE_COMPTABLE_ANALYTICS = 45;
  const CSV_CIAPL_CODE_COMPTABLE_VENTE = 46;
  const CSV_CIAPL_DATE_CREATION = 47;
  const CSV_CIAPL_DATE_DERNIERE_MODIFICATION = 48;
  const CSV_CIAPL_STQZ = 49;
  const CSV_CIAPL_CEPAGE = 50;
  const CSV_CIAPL_CODE_STATS = 51;
  const CSV_CIAPL_SUSPENDU = 52;
  const CSV_CIAPL_COULEUR = 53;
  const CSV_CIAPL_SUR_LIE = 54;
  const CSV_CIAPL_AGREMENT_LABEL = 55;
  const CSV_CIAPL_REGION_VITICOLE = 56;
  const CSV_CIAPL_MOUSSEUX = 57;

  const CSV_PRODUIT_INTERPRO = 58;
  const CSV_PRODUIT_CATEGORIE_LIBELLE = 59;    //CATEGORIE == CERTIFICATION
  const CSV_PRODUIT_CATEGORIE_CODE = 60;       //CATEGORIE == CERTIFICATION
  const CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT = 'C';
  const CSV_PRODUIT_GENRE_LIBELLE = 61;
  const CSV_PRODUIT_GENRE_CODE = 62;
  const CSV_PRODUIT_GENRE_CODE_APPLICATIF_DROIT = 'G';
  const CSV_PRODUIT_DENOMINATION_LIBELLE = 63; //DENOMINATION == APPELLATION
  const CSV_PRODUIT_DENOMINATION_CODE = 64;    //DENOMINATION == APPELLATION
  const CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT = 'A';
  const CSV_PRODUIT_MENTION_LIBELLE = 65;
  const CSV_PRODUIT_MENTION_CODE = 66;
  const CSV_PRODUIT_LIEU_LIBELLE = 67;
  const CSV_PRODUIT_LIEU_CODE = 68;
  const CSV_PRODUIT_COULEUR_LIBELLE = 69;
  const CSV_PRODUIT_COULEUR_CODE = 70;
  const CSV_PRODUIT_CEPAGE_LIBELLE = 71;
  const CSV_PRODUIT_CEPAGE_CODE = 72;

  const CSV_TYPE_PRODUIT_INDETERMINE = 0;
  const CSV_TYPE_PRODUIT_RAISINS = 1;
  const CSV_TYPE_PRODUIT_MOUTS = 2;
  const CSV_TYPE_PRODUIT_VIN_VRAC = 3;
  const CSV_TYPE_PRODUIT_TIRE_BOUCHE = 5;
  const CSV_TYPE_PRODUIT_VIN_LATTES = 6;
  const CSV_TYPE_PRODUIT_VIN_CRD = 7;
  const CSV_TYPE_PRODUIT_VIN_BOUTEILLE = 8;

  const CSV_UNITE_VOLUME_HL = 'hl';
  const CSV_UNITE_VOLUME_KG = 'kg';

  const CSV_TYPE_CONTRAT_SORTIES_COOP = 'C';
  const CSV_TYPE_CONTRAT_PLURIANNUEL_PRIX_A_DEFINIR = 'D';
  const CSV_TYPE_CONTRAT_FITRANEG = 'F';
  const CSV_TYPE_CONTRAT_FERMAGE = 'M';
  const CSV_TYPE_CONTRAT_PLURIANNUEL = 'P';
  const CSV_TYPE_CONTRAT_QUINQUENNAL = 'Q';
  const CSV_TYPE_CONTRAT_PAS_TRANSACTION_FINANCIERE = 'T';
  const CSV_TYPE_CONTRAT_VINAIGRERIE = 'V';

  private function verifyCsvLine($line) {
    /*if (!preg_match('/[0-9]+/', $line[self::CSV_CODE_PARTENAIRE])) {

      throw new Exception(sprintf('Numero de dossier invalide : %s', $line[self::CSV_CODE_PARTENAIRE]));
    }*/
  }

  public function importVracs() {
    $this->errors = array();
    $vracs = array();
    echo "started parse csv \n";
    $csvs = $this->getCsv();
    try {
      foreach ($csvs as $line) {
      	$this->verifyCsvLine($line);

        $type_transaction = $this->convertTypeTransaction($line[self::CSV_TYPE_PRODUIT]);   
        
        if (!$type_transaction) {
          
          continue;
        }

        if (!isset($line[self::CSV_PRODUIT_INTERPRO])) {
          continue;
        }
        
        echo $type_transaction . "\n";

        echo $this->constructNumeroContrat($line)."\n";

        $v = VracClient::getInstance()->findByNumContrat($this->constructNumeroContrat($line), acCouchdbClient::HYDRATE_JSON);
        
        if (!$v) {
          $v = new Vrac();
          $v->numero_contrat = $this->constructNumeroContrat($line);
        }

        $date = $this->getDateCreationObject($line[self::CSV_DATE_SIGNATURE_OU_CREATION]);
        $v->date_signature =  $date->format('d/m/Y');
        $v->date_stats =  $date->format('d/m/Y');
        $v->valide->date_saisie = $date->format('d/m/Y');

        $v->vendeur_identifiant = 'ETABLISSEMENT-'.$line[self::CSV_CODE_VITICULTEUR];
        $v->acheteur_identifiant = 'ETABLISSEMENT-'.$line[self::CSV_CODE_NEGOCIANT];
        $v->mandataire_identifiant = null;

        if ($line[self::CSV_CODE_COURTIER]) {
          $v->mandataire_identifiant   = 'ETABLISSEMENT-'.$line[self::CSV_CODE_COURTIER];
        }

        $v->produit = 'declaration/certifications/AOC';

        $v->produit = $this->getHash($line);

        if($line[self::CSV_CIAPL_SUR_LIE] == "O") {
          $v->label->add(null, "LIE");
        }      

        if (!$v->getVendeurObject() || !$v->getAcheteurObject()) {
          echo "Les etablissements n'existes pas \n";
          continue;
        }

        if (!$v->mandataire_identifiant || !$v->getMandataireObject()) {
          $v->mandataire_identifiant = null;
        } else {
          $v->mandataire_exist = 1;
        }

        $v->type_transaction = $type_transaction;
        
        if (in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE))) {
          $v->bouteilles_contenance = $line[self::CSV_COEF_CONVERSION_PRIX] * 100;
          $v->bouteilles_quantite = $line[self::CSV_VOLUME_PROPOSE_HL] * $v->bouteilles_contenance / 10000;
        } elseif(in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_MOUTS,
                                                      VracClient::TYPE_TRANSACTION_VIN_VRAC))) {
          $v->jus_quantite = $line[self::CSV_VOLUME_PROPOSE_HL] * 1;
        } elseif(in_array($v->type_transaction, array(VracClient::TYPE_TRANSACTION_RAISINS))) {
          $v->raisin_quantite = $line[self::CSV_VOLUME_PROPOSE_HL] * $line[self::CSV_COEF_CONVERSION_PRIX];
        }

        $v->volume_propose = $line[self::CSV_VOLUME_PROPOSE_HL] * 1;

        $v->volume_enleve = $line[self::CSV_VOLUME_ENLEVE_HL] * 1;

        $v->prix_unitaire = $line[self::CSV_PRIX_AU_LITRE] * 1;

        $v->type_contrat = $this->convertTypeContrat($line[self::CSV_TYPE_CONTRAT]);

        //$v->prix_variable = $this->convertPrixVariable($line[self::CSV_INDICATEUR_PRIX_VARIABLE]);

        $v->cvo_nature = $this->convertCvoNature($line[self::CSV_TYPE_CONTRAT]);

        $v->setInformations();

        $v->update(); 

        $v->save();

      	$vracs[$v->numero_contrat] = $v;
      }
    } catch(Execption $e) {
      $this->error[] = $e->getMessage();
    }
    return $vracs;
  }

  public function getErrors() {

    return $this->errors;
  }

  protected function getDateCreationObject($date) {
    return new DateTime($date);
  }

  protected function constructNumeroContrat($line) {

    return $this->getDateCreationObject($line[self::CSV_DATE_SIGNATURE_OU_CREATION])->format('Ymd') . $line[self::CSV_NUMERO_CONTRAT];
  }

  protected function convertTypeTransaction($type) {
    $type_transactions = array(
      self::CSV_TYPE_PRODUIT_INDETERMINE => null,
      self::CSV_TYPE_PRODUIT_RAISINS => VracClient::TYPE_TRANSACTION_RAISINS,
      self::CSV_TYPE_PRODUIT_MOUTS => VracClient::TYPE_TRANSACTION_MOUTS,
      self::CSV_TYPE_PRODUIT_VIN_VRAC => VracClient::TYPE_TRANSACTION_VIN_VRAC,
      self::CSV_TYPE_PRODUIT_TIRE_BOUCHE => null,
      self::CSV_TYPE_PRODUIT_VIN_LATTES => null,
      self::CSV_TYPE_PRODUIT_VIN_CRD => null,
      self::CSV_TYPE_PRODUIT_VIN_BOUTEILLE => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
    );

    if (array_key_exists($type, $type_transactions)) {

      return $type_transactions[$type];
    }

    return null;
  }

  protected function convertTypeContrat($type) {
    $type_contrats = array(
        self::CSV_TYPE_CONTRAT_SORTIES_COOP => null,
        self::CSV_TYPE_CONTRAT_PLURIANNUEL_PRIX_A_DEFINIR => VracClient::TYPE_CONTRAT_PLURIANNUEL,
        self::CSV_TYPE_CONTRAT_FITRANEG => null,
        self::CSV_TYPE_CONTRAT_FERMAGE => null,
        self::CSV_TYPE_CONTRAT_PLURIANNUEL => VracClient::TYPE_CONTRAT_PLURIANNUEL,
        self::CSV_TYPE_CONTRAT_QUINQUENNAL => null,
        self::CSV_TYPE_CONTRAT_PAS_TRANSACTION_FINANCIERE => null,
        self::CSV_TYPE_CONTRAT_VINAIGRERIE => null,
    );

    if (array_key_exists($type, $type_contrats)) {

      return $type_contrats[$type];
    }

    return null;
  }

  protected function convertCvoNature($type) {
    $type_contrats = array(
        self::CSV_TYPE_CONTRAT_PAS_TRANSACTION_FINANCIERE => VracClient::CVO_NATURE_NON_FINANCIERE,
        self::CSV_TYPE_CONTRAT_VINAIGRERIE => VracClient::CVO_NATURE_VINAIGRERIE,
    );

    if (array_key_exists($type, $type_contrats)) {

      return $type_contrats[$type];
    }

    return null;
  }

  protected function convertOuiNon($indicateur) {

    return (int) ($indicateur == 'O');
  }

  private function getHash($line) 
  {
    $hash  = 'declaration/certifications/'.$this->getKey($line[self::CSV_PRODUIT_CATEGORIE_CODE]);
    $hash .= '/genres/'.$this->getKey($line[self::CSV_PRODUIT_GENRE_CODE], true);
    $hash .= '/appellations/'.$this->getKey($line[self::CSV_PRODUIT_DENOMINATION_CODE], true);
    $hash .= '/mentions/'.$this->getKey($line[self::CSV_PRODUIT_MENTION_CODE], true);
    $hash .= '/lieux/'.$this->getKey($line[self::CSV_PRODUIT_LIEU_CODE], true);
    $hash .= '/couleurs/'.strtolower($this->couleurKeyToCode($line[self::CSV_PRODUIT_COULEUR_CODE]));
    $hash .= '/cepages/'.$this->getKey($line[self::CSV_PRODUIT_CEPAGE_CODE], true);
    return $hash;
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

}