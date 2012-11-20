<?php

class importDRMTask extends importAbstractTask
{

  const CSV_LIGNE_ID = 0;
  const CSV_LIGNE_TYPE = 1;

  const CSV_LIGNE_TYPE_CONTRAT = '1.CONTRAT';
  const CSV_LIGNE_TYPE_VENTE = '2.VENTE';
  const CSV_LIGNE_TYPE_DIVERS = '3.DIVERS';
  const CSV_LIGNE_TYPE_CAVE_VITI = '4.CAVE-VITI';
  const CSV_LIGNE_TYPE_CAVE_COOP = '5.CAVE-COOP';
  const CSV_LIGNE_TYPE_TRANSFERT_SORTIE = '6.TRANSFERT-SORTIE';
  const CSV_LIGNE_TYPE_TRANSFERT_ENTREE = '7.TRANSFERT-ENTREE';
  const CSV_LIGNE_TYPE_MOUVEMENT = '8.MOUVEMENT';

  const CSV_CONTRAT_DOSSIER = 2;
  const CSV_CONTRAT_CAMPAGNE = 3;
  const CSV_CONTRAT_NUMERO_CONTRAT = 4;
  const CSV_CONTRAT_NUMERO_ENLEVEMENT = 5;
  const CSV_CONTRAT_PERIODE_ENLEVEMENT = 6;
  const CSV_CONTRAT_VOLUME_ENLEVE_UNITE_ACHAT = 7;
  const CSV_CONTRAT_UNITE_ACHAT = 8;
  const CSV_CONTRAT_COEF_CONVERSION_VOLUME = 9;
  const CSV_CONTRAT_MODE_CONVERSION_VOLUME = 10;
  const CSV_CONTRAT_VOLUME_ENLEVE_HL = 11;
  const CSV_CONTRAT_COTISATION_CVO_NEGOCIANT = 12;
  const CSV_CONTRAT_COTISATION_CVO_VITICULTEUR = 13;
  const CSV_CONTRAT_DATE_ENLEVEMENT = 16;
  const CSV_CONTRAT_CAMPAGNE_FACTURATION_VITICULTEUR = 17;
  const CSV_CONTRAT_SITE_VITICULTEUR = 18;
  const CSV_CONTRAT_NUMERO_FACTURE_VITICULTEUR = 19;
  const CSV_CONTRAT_CAMPAGNE_FACTURATION_NEGOCIANT = 20;
  const CSV_CONTRAT_SITE_NEGOCIANT = 21;
  const CSV_CONTRAT_NUMERO_FACTURE_NEGOCIANT = 22;
  const CSV_CONTRAT_DATE_ENREGISTREMENT = 26;
  const CSV_CONTRAT_CODE_RECETTE_LOCALE = 27;
  const CSV_CONTRAT_CODE_VITICULTEUR = 28;
  const CSV_CONTRAT_CODE_CHAI_CAVE = 29;
  const CSV_CONTRAT_CODE_NEGOCIANT = 30;
  const CSV_CONTRAT_CODE_COURTIER = 31;
  const CSV_CONTRAT_CODE_APPELLATION = 32;
  const CSV_CONTRAT_TYPE_PRODUIT = 33;
  const CSV_CONTRAT_MILLESIME = 34;
  const CSV_CONTRAT_COTISATION_CVO_NEGOCIANT_TOTAL = 35;
  const CSV_CONTRAT_COTISATION_CVO_VITICULTEUR_TOTAL = 36;
  const CSV_CONTRAT_VOLUME = 37;
  const CSV_CONTRAT_UNITE_VOLUME = 38;
  const CSV_CONTRAT_COEF_CONVERSION_VOLUME_TOTAL = 39;
  const CSV_CONTRAT_MODE_CONVERSION_VOLUME_TOTAL = 40;
  const CSV_CONTRAT_VOLUME_PROPOSE_HL = 41;
  const CSV_CONTRAT_VOLUME_ENLEVE_TOTAl_HL = 42;
  const CSV_CONTRAT_PRIX_VENTE = 43;
  const CSV_CONTRAT_CODE_DEVISE = 44;
  const CSV_CONTRAT_UNITE_PRIX_VENTE = 45;
  const CSV_CONTRAT_COEF_CONVERSION_PRIX = 46;
  const CSV_CONTRAT_MODE_CONVERSION_PRIX = 47;
  const CSV_CONTRAT_PRIX_AU_LITRE = 48;
  const CSV_CONTRAT_CONTRAT_SOLDE = 49;
  const CSV_CONTRAT_DATE_SIGNATURE_OU_CREATION = 50;
  const CSV_CONTRAT_DATE_DERNIERE_MODIFICATION = 51;
  const CSV_CONTRAT_CODE_SAISIE = 52;
  const CSV_CONTRAT_DATE_LIVRAISON = 53;
  const CSV_CONTRAT_CODE_MODE_PAIEMENT = 54;
  const CSV_CONTRAT_COMPOSTAGE = 55;
  const CSV_CONTRAT_TYPE_CONTRAT = 56;
  const CSV_CONTRAT_ATTENTE_ORIGINAL = 57;
  const CSV_CONTRAT_CATEGORIE_VIN = 58;
  const CSV_CONTRAT_CEPAGE = 59;
  const CSV_CONTRAT_MILLESIME_ANNEE = 60;
  const CSV_CONTRAT_PRIX_HORS_CVO = 61;
  const CSV_CONTRAT_PRIX_CVO_INCLUSE = 61;
  const CSV_CONTRAT_TAUX_CVO_GLOBAL = 63;

  const CSV_VENTE_DOSSIER = 2;
  const CSV_VENTE_CAMPAGNE = 3;
  const CSV_VENTE_NUMERO_SORTIE = 4;
  const CSV_VENTE_MOIS_SORTIE = 5;
  const CSV_VENTE_CODE_PARTENAIRE = 6;
  const CSV_VENTE_CODE_CHAI = 7;
  const CSV_VENTE_CODE_RECETTE_LOCALE = 8;
  const CSV_VENTE_DATE_CREATION = 9;
  const CSV_VENTE_DATE_MODIFICATION = 10;
  const CSV_VENTE_CODE_SAISIS = 11;
  const CSV_VENTE_DATE_SORTIE = 12;
  const CSV_VENTE_DATE_SAISIE_SORTIE = 13;
  const CSV_VENTE_NUMERO_LIGNE = 16;
  const CSV_VENTE_CODE_APPELLATION = 17;
  const CSV_VENTE_TYPE_VIN = 18;
  const CSV_VENTE_COTISATION_VITICULEUR_VENTE_DIRECTE = 19;
  const CSV_VENTE_VOLUME_EXPORT = 20;
  const CSV_VENTE_VOLUME_CONGE = 21;
  const CSV_VENTE_VOLUME_CRD = 22;
  const CSV_VENTE_FACTURE_INDICATEUR = 23;
  const CSV_VENTE_MILLESIME_INDICATEUR = 24;
  const CSV_VENTE_CODE_PAYS = 25;
  const CSV_VENTE_CAMPAGNE_FACTURE = 26;
  const CSV_VENTE_CODE_SITE = 27;
  const CSV_VENTE_NUMERO_FACTURE = 28;

  const CSV_DIVERS_DOSSIER = 2;
  const CSV_DIVERS_CAMPAGNE = 3;
  const CSV_DIVERS_NUMERO_MOUVEMENT = 4;
  const CSV_DIVERS_ANNULATION = 5;
  const CSV_DIVERS_CODE_PARTENAIRE = 6;
  const CSV_DIVERS_CODE_CHAI = 7;
  const CSV_DIVERS_CODE_APPELLATION_1 = 8;
  const CSV_DIVERS_CODE_APPELLATION_2 = 9;
  const CSV_DIVERS_TEXTE_MOUVEMENT = 10;
  const CSV_DIVERS_CODE_MOUVEMENT = 11;
  const CSV_DIVERS_DATE_MOUVEMENT = 12;
  const CSV_DIVERS_MOIS_MOUVEMENT = 13;
  const CSV_DIVERS_DATE_HEURE_SAISIE = 14;
  const CSV_DIVERS_VOLUME_HL = 15;
  const CSV_DIVERS_CODE_UTILISATEUR = 16;
  const CSV_DIVERS_STOCK_DISPO_AVANT = 17;
  const CSV_DIVERS_STOCK_DISPO_APRES = 18;
  const CSV_DIVERS_STOCK_DISPO_PRECEDENT_AVANT = 19;
  const CSV_DIVERS_STOCK_DISPO_PRECEDENT_APRES = 20;
  const CSV_DIVERS_CODE_UTILISATEUR_SUPPRESSION = 21;
  const CSV_DIVERS_DATE_SUPPRESSION = 22;

  const CSV_TRANSFERT_DOSSIER = 2;
  const CSV_TRANSFERT_CAMPAGNE = 3;
  const CSV_TRANSFERT_NUMERO_MOUVEMENT = 4;
  const CSV_TRANSFERT_ANNULATION = 5;
  const CSV_TRANSFERT_CODE_PARTENAIRE = 6;
  const CSV_TRANSFERT_CODE_CHAI = 7;
  const CSV_TRANSFERT_CODE_PARTENAIRE_DESTINATAIRE = 8;
  const CSV_TRANSFERT_CODE_CHAI_DESTINATAIRE = 9;
  const CSV_TRANSFERT_CODE_APPELLATION = 10;
  const CSV_TRANSFERT_DATE_MOUVEMENT = 11;
  const CSV_TRANSFERT_MOIS_MOUVEMENT = 12;
  const CSV_TRANSFERT_DATE_HEURE_SAISIE = 13;
  const CSV_TRANSFERT_VOLUME_HL = 14;
  const CSV_TRANSFERT_CODE_UTILISATEUR = 15;
  const CSV_TRANSFERT_STOCK_DISPO_AVANT = 16;
  const CSV_TRANSFERT_STOCK_DISPO_APRES = 17;
  const CSV_TRANSFERT_CODE_UTILISATEUR_SUPPRESSION = 18;
  const CSV_TRANSFERT_DATE_SUPPRESSION = 19;

  const CSV_CAVE_DOSSIER = 2;
  const CSV_CAVE_CAMPAGNE = 3;
  const CSV_CAVE_NUMERO_MOUVEMENT = 4;
  const CSV_CAVE_ANNULATION = 5;
  const CSV_CAVE_CODE_COOPERATEUR = 6;
  const CSV_CAVE_CODE_COOPERATEUR_CHAI = 7;
  const CSV_CAVE_CODE_VITICULTEUR = 8;
  const CSV_CAVE_CODE_VITICULTEUR_CHAI = 9;
  const CSV_CAVE_CODE_APPELLATION = 10;
  const CSV_CAVE_DATE_MOUVEMENT = 11;
  const CSV_CAVE_DATE_HEURE_SAISIE = 12;
  const CSV_CAVE_VOLUME_ENTREE = 13;
  const CSV_CAVE_VOLUME_SORTIE = 14;
  const CSV_CAVE_CODE_UTILISATEUR = 15;
  const CSV_CAVE_STOCK_DISPO_AVANT = 16;
  const CSV_CAVE_STOCK_DISPO_APRES = 17;
  const CSV_CAVE_CODE_UTILISATEUR_SUPPRESSION = 18;
  const CSV_CAVE_DATE_SUPPRESSION = 19;  
  const CSV_CAVE_MOIS_MOUVEMENT = 20;
  const CSV_CAVE_NUMERO_DOCUMENT = 21;

  const CSV_MOUVEMENT_DOSSIER = 2;
  const CSV_MOUVEMENT_CAMPAGNE = 3;
  const CSV_MOUVEMENT_CODE_PARTENAIRE = 4;
  const CSV_MOUVEMENT_CODE_CHAI = 5;
  const CSV_MOUVEMENT_CODE_APPELLATION = 6;
  const CSV_MOUVEMENT_CODE_MOUVEMENT = 7;
  const CSV_MOUVEMENT_DATE_MOUVEMENT = 8;
  const CSV_MOUVEMENT_DATE_HEURE_SAISIE = 9;
  const CSV_MOUVEMENT_STOCK_FIN_CAMPAGNE = 10;
  const CSV_MOUVEMENT_VOLUME_CONTRAT = 11;
  const CSV_MOUVEMENT_VOLUME_SORTIE = 12;
  const CSV_MOUVEMENT_VOLUME_ENLEVE = 13;
  const CSV_MOUVEMENT_VOLUME_CONTRAT_NOUVELLE_RECOLTE = 14;
  const CSV_MOUVEMENT_VOLUME_AGREE_COMMERCIALISABLE = 15;
  const CSV_MOUVEMENT_VOLUME_AGREE_BLOQUE = 16;
  const CSV_MOUVEMENT_VOLUME_SUSCEPTIBLE_RECLASSEMENT = 17;
  const CSV_MOUVEMENT_VOLUME_REGULARISATION = 18;
  const CSV_MOUVEMENT_VOLUME_VOLUME_BLOQUE_CAMPAGNE_PRECEDENTE = 20;
  const CSV_MOUVEMENT_STOCK_COURANT = 21;
  const CSV_MOUVEMENT_TYPE_DOCUMENT = 22;
  const CSV_MOUVEMENT_NUMERO_DOCUMENT = 23;
  const CSV_MOUVEMENT_COMMENTAIRE = 24;
  const CSV_MOUVEMENT_VOLUME_RECOLTE = 25;
  const CSV_MOUVEMENT_SUPERFICIE_RECOLTE = 26;

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
    $this->name             = 'drm';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importVrac|INFO] task does things.
Call it with:

  [php symfony import:drm|INFO]
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

      if($id && $id != $this->getId($data)) {
        $this->importDRM($lines);
        $lines = array();
      }
      
      $id = $this->getId($data);
      $lines[$i] = $data;
      $i++;
    }

  }

  protected function getCodeProduit($line) {
    
    return substr($line[self::CSV_LIGNE_ID], 15, 4);
  }

  protected function getId($line) {
    
    return DRMClient::getInstance()->buildId($this->getIdentifiant($line), $this->getPeriode($line));
  }

  protected function getPeriode($line) {
     
    return DRMClient::getInstance()->buildPeriode(substr($line[self::CSV_LIGNE_ID], 0, 4), substr($line[self::CSV_LIGNE_ID], 13, 2));
  }

  protected function getIdentifiant($line) {

    return substr($line[self::CSV_LIGNE_ID], 5, 6);
  }

  public function importDRM($lines) {
    $drm = null;

    foreach($lines as $i => $line) {
      try{
        $this->verifyLine($line);
        $drm = $this->importLigne($drm, $line);
      } catch (Exception $e) {
        $this->log(sprintf("%s (ligne %s) : %s", $e->getMessage(), $i, implode($line, ";")));
        return;
      }
    }

    $drm->valide->date_saisie = date('c', strtotime($drm->getDate()));
    $drm->valide->date_signee = date('c', strtotime($drm->getDate()));
    $drm->update();
    $drm->validate(array('no_vracs' => true));
    $drm->save();
  }

  public function importLigne($drm, $line) {
    if (is_null($drm)) {
      $drm = DRMClient::getInstance()->findOrCreateByIdentifiantAndPeriode($this->getIdentifiant($line), $this->getPeriode($line));

      if(!$drm->getEtablissement()) {
        throw new sfException(sprintf("L'etablissement %s n'existe pas", $line[self::CSV_CODE_VITICULTEUR]));
      }
    }

    switch($line[self::CSV_LIGNE_TYPE]) {
      case self::CSV_LIGNE_TYPE_CONTRAT:
        $this->importLigneContrat($drm, $line);
        break;
      case self::CSV_LIGNE_TYPE_VENTE:
        $this->importLigneVente($drm, $line);
        break;
      case self::CSV_LIGNE_TYPE_DIVERS:
        $this->importLigneDivers($drm, $line);
        break;
      case self::CSV_LIGNE_TYPE_CAVE_VITI:
      case self::CSV_LIGNE_TYPE_CAVE_COOP:
        $this->importLigneCave($drm, $line);
        break;
      case self::CSV_LIGNE_TYPE_TRANSFERT_SORTIE:
      case self::CSV_LIGNE_TYPE_TRANSFERT_ENTREE:
        $this->importLigneTransfert($drm, $line);
        break;
      case self::CSV_LIGNE_TYPE_MOUVEMENT:
        $this->importLigneMouvement($drm, $line);
        break;
      default:
        throw new sfException(sprintf("Le type de ligne '%s' n'est pas pris en compte", $line[self::CSV_LIGNE_TYPE]));
    }

    return $drm;
  }

  public function importLigneContrat($drm, $line) {
    $produit = $drm->addProduit($this->getHash($this->getCodeProduit($line)));

    $numero_contrat = $this->constructNumeroContrat($line);
    $contrat = VracClient::getInstance()->findByNumContrat($numero_contrat);

    if(!$contrat) {
      throw new sfException(sprintf("Le contrat '%s' n'existe pas", $numero_contrat));
    }

    $produit->sorties->vrac_details->add(null, array("identifiant" => $numero_contrat,
                                                     "volume" => $this->convertToFloat($line[self::CSV_CONTRAT_VOLUME_ENLEVE_HL]),
                                                     "date_enlevement" => $line[self::CSV_CONTRAT_DATE_ENLEVEMENT]));
  }

  public function importLigneVente($drm, $line) {
    $produit = $drm->addProduit($this->getHash($this->getCodeProduit($line)));

    $produit->sorties->export_details->add(null, array("identifiant" => $line[self::CSV_VENTE_CODE_PAYS],
                                                       "volume" => $this->convertToFloat($line[self::CSV_VENTE_VOLUME_EXPORT]),
                                                       "date_enlevement" => $line[self::CSV_VENTE_DATE_SORTIE]));

    $produit->sorties->vracsanscontrat = $this->convertToFloat($line[self::CSV_VENTE_VOLUME_CONGE]);
    $produit->sorties->bouteille = $this->convertToFloat($line[self::CSV_VENTE_VOLUME_CRD]);
  }

  public function importLigneDivers($drm, $line) {
    $produit = $drm->addProduit($this->getHash($this->getCodeProduit($line)));

    if($line[self::CSV_DIVERS_CODE_MOUVEMENT] == 86) {
      $produit->sorties->regularisation = $this->convertToFloat($line[self::CSV_DIVERS_VOLUME_HL]);
      return;
    }

    if($line[self::CSV_DIVERS_CODE_MOUVEMENT] == 82) {
      $produit->sorties->distillation = $this->convertToFloat($line[self::CSV_DIVERS_VOLUME_HL]);
      return;
    }

    if($line[self::CSV_DIVERS_CODE_MOUVEMENT] == 81) {
      $produit->sorties->declassement = $this->convertToFloat($line[self::CSV_DIVERS_VOLUME_HL]);
      return;
    }

    if($line[self::CSV_DIVERS_CODE_MOUVEMENT] == 80) {
      $produit->sorties->fermagemetayage = $this->convertToFloat($line[self::CSV_DIVERS_VOLUME_HL]);
      return;
    }

    if($line[self::CSV_DIVERS_TEXTE_MOUVEMENT] == "REPLI") {
      $produit->sorties->repli = $this->convertToFloat($line[self::CSV_DIVERS_VOLUME_HL]);
      $produit_repli = $drm->addProduit($this->getHash($line[self::CSV_DIVERS_CODE_APPELLATION_2]));
      $produit_repli->entrees->repli = $this->convertToFloat($line[self::CSV_DIVERS_VOLUME_HL]);
      return;
    }

    throw new sfException(sprintf("Ce mouvement n'est pas prit en compte '%s;%s'", $line[self::CSV_DIVERS_TEXTE_MOUVEMENT], $line[self::CSV_DIVERS_CODE_MOUVEMENT]));
  }


  public function importLigneCave($drm, $line) {
    $produit = $drm->addProduit($this->getHash($this->getCodeProduit($line)));

    if($line[self::CSV_LIGNE_TYPE] == self::CSV_LIGNE_TYPE_CAVE_VITI) {
      $etablissement = EtablissementClient::getInstance()->find($line[self::CSV_CAVE_CODE_COOPERATEUR]);
      if(!$etablissement) {

        throw new sfException(sprintf("L'établissement cave coop '%s' n'existe pas", $line[self::CSV_CAVE_CODE_COOPERATEUR]));
      }

      $produit->sorties->cooperative_details->add(null, array("identifiant" => $etablissement->getIdentifiant(),
                                                              "volume" => $this->convertToFloat($line[self::CSV_CAVE_VOLUME_SORTIE]),
                                                              "date_enlevement" => $line[self::CSV_CAVE_DATE_MOUVEMENT]));
    }

    if($line[self::CSV_LIGNE_TYPE] == self::CSV_LIGNE_TYPE_CAVE_COOP) {
      $produit->entrees->cooperative = $this->convertToFloat($line[self::CSV_CAVE_VOLUME_ENTREE]);
    }
  }

  public function importLigneTransfert($drm, $line) {
    $produit = $drm->addProduit($this->getHash($this->getCodeProduit($line)));

    if($line[self::CSV_LIGNE_TYPE] == self::CSV_LIGNE_TYPE_TRANSFERT_SORTIE) {
      $produit->sorties->cession = $this->convertToFloat($line[self::CSV_TRANSFERT_VOLUME_HL]);
    }

    if($line[self::CSV_LIGNE_TYPE] == self::CSV_LIGNE_TYPE_TRANSFERT_ENTREE) {
      $produit->entrees->transfert = $this->convertToFloat($line[self::CSV_TRANSFERT_VOLUME_HL]);
    }
  }

  public function importLigneMouvement($drm, $line) {
  }

  protected function verifyLine($line) {
    if (!preg_match('/[0-9]{4}-[0-9]{6}-[0-9]{2}-[0-9]{4}/', $line[self::CSV_LIGNE_ID])) {

      throw new sfException(sprintf("L'id '%s' de la ligne est incorrect", $line[self::CSV_LIGNE_ID]));
    }

    $this->getHash($this->getCodeProduit($line));

    switch($line[self::CSV_LIGNE_TYPE]) {
      case self::CSV_LIGNE_TYPE_CONTRAT:
        $this->verifyLineContrat($line);
        break;
      case self::CSV_LIGNE_TYPE_DIVERS:
        $this->verifyLineDivers($line);
        break;
      case self::CSV_LIGNE_TYPE_CAVE_VITI:
      case self::CSV_LIGNE_TYPE_CAVE_COOP:
        $this->verifyLineCave($line);
        break;
      case self::CSV_LIGNE_TYPE_TRANSFERT_SORTIE:
      case self::CSV_LIGNE_TYPE_TRANSFERT_ENTREE:
        $this->verifyLineTransfert($line);
        break;
      case self::CSV_LIGNE_TYPE_VENTE:
        $this->verifyLineVente($line);
        break;
    }
  }

  protected function verifyLineContrat($line) {
    $this->verifyFloat($line[self::CSV_CONTRAT_VOLUME_ENLEVE_HL]);
  }

  protected function verifyLineVente($line) {
    $this->verifyFloat($line[self::CSV_VENTE_VOLUME_CRD]);
    $this->verifyFloat($line[self::CSV_VENTE_VOLUME_CONGE]);
    $this->verifyFloat($line[self::CSV_VENTE_VOLUME_EXPORT]);
  }

  protected function verifyLineDivers($line) {
    $this->verifyFloat($line[self::CSV_DIVERS_VOLUME_HL]);

    if($line[self::CSV_DIVERS_TEXTE_MOUVEMENT] == "REPLI") {
      $this->getHash($line[self::CSV_DIVERS_CODE_APPELLATION_2]);
    }
  }

  protected function verifyLineTransfert($line) {
    $this->verifyFloat($line[self::CSV_TRANSFERT_VOLUME_HL]);
  }

  protected function verifyLineCave($line) {
    if($line[self::CSV_LIGNE_TYPE] == self::CSV_LIGNE_TYPE_CAVE_VITI) {
      $this->verifyFloat($line[self::CSV_CAVE_VOLUME_SORTIE]);
    }

    if($line[self::CSV_LIGNE_TYPE] == self::CSV_LIGNE_TYPE_CAVE_COOP) {
      $this->verifyFloat($line[self::CSV_CAVE_VOLUME_ENTREE]);
    }
  }

  protected function verifyLineMouvement($line) {
  }

  protected function verifyFloat($value) {
    $value = $this->convertToFloat($value);
    if(!(is_float($value) && $value >= 0)) {
      throw new sfException(sprintf("Nombre flottant '%s' invalide", $value));
    }
  }

  protected function constructNumeroContrat($line) {

      return $this->convertToDateObject($line[self::CSV_CONTRAT_DATE_SIGNATURE_OU_CREATION])->format('Ymd') . sprintf("%04d", $line[self::CSV_CONTRAT_NUMERO_CONTRAT]);
  }
}
