<?php

class EtablissementClient extends acCouchdbClient {

    /**
     *
     * @return EtablissementClient
     */
    const REGION_HORS_CVO = 'REGION_HORS_CVO';
    const REGION_CVO = 'REGION_CVO';
    const RECETTE_LOCALE = 'RECETTE_LOCALE';
    const TYPE_DR_DRM = 'DRM';
    const TYPE_DR_DRA = 'DRA';

    const TYPE_LIAISON_BAILLEUR = 'BAILLEUR';
    const TYPE_LIAISON_METAYER = 'METAYER';

    const TYPE_LIAISON_FERMIER = 'FERMIER';

    const TYPE_LIAISON_COOPERATIVE = 'COOPERATIVE'; // a pour coopérative
    const TYPE_LIAISON_COOPERATEUR = 'COOPERATEUR'; // a pour coopérateur

    const TYPE_LIAISON_NEGOCIANT = 'NEGOCIANT'; //à pour les negociants
    const TYPE_LIAISON_VENDEUR_VRAC = 'VENDEUR_VRAC';//à pour les vendeur de vin en vrac

    const TYPE_LIAISON_NEGOCIANT_VINIFICATEUR = 'NEGOCIANT_VINIFICATEUR'; //à pour les négociant vinificateur
    const TYPE_LIAISON_APPORTEUR_RAISIN = 'APPORTEUR_RAISIN'; //à pour apporteur de raisins

    const TYPE_LIAISON_HEBERGE_TIERS = 'HEBERGE_TIERS'; //Hébergé chez un tiers
    const TYPE_LIAISON_HEBERGE = 'HEBERGE'; //Heberge

    const TYPE_LIAISON_LABO = "LABO";
    const TYPE_LIAISON_ANALYSE_DE = "ANALYSE_DE";

    const STATUT_ACTIF = 'ACTIF'; #'actif';
    const STATUT_SUSPENDU = 'SUSPENDU'; #'suspendu';
    const OUI = 'OUI';
    const NON = 'NON';
    const RELANCE_DS_OUI = self::OUI;
    const RELANCE_DS_NON = self::NON;
    const RAISINS_MOUTS_OUI = self::OUI;
    const RAISINS_MOUTS_NON = self::NON;
    const EXCLUSION_DRM_OUI = self::OUI;
    const EXCLUSION_DRM_NON = self::NON;
    const REGIME_CRD_PERSONNALISE = 'PERSONNALISE';
    const REGIME_CRD_COLLECTIF_ACQUITTE = 'COLLECTIFACQUITTE';
    const REGIME_CRD_COLLECTIF_SUSPENDU = 'COLLECTIFSUSPENDU';
    const CAUTION_DISPENSE = 'DISPENSE';
    const CAUTION_CAUTION = 'CAUTION';
    const NATURE_INAO_PRODUCTEUR_INDIVIDUEL = 'Producteur individuel';
    const NATURE_INAO_COOPERATIVE = 'Coopérative';
    const NATURE_INAO_UNION_DE_COOPERATIVES = 'Union de coopératives';
    const NATURE_SOCIETE_CIVILE = 'Société civile (GFA, GAEC, ...)';
    const NATURE_INAO_SICA = 'SICA';
    const NATURE_INAO_SOCIETE_COMMERCIALE = 'Société commerciale (négociant)';
    const NATURE_INAO_AUTRE = 'Autre';

    const CHAI_ATTRIBUT_VINIFICATION = "VINIFICATION";
    const CHAI_ATTRIBUT_CONDITIONNEMENT = "CONDITIONNEMENT";
    const CHAI_ATTRIBUT_STOCKAGE = "STOCKAGE";
    const CHAI_ATTRIBUT_STOCKAGE_VRAC = "STOCKAGE_VRAC";
    const CHAI_ATTRIBUT_STOCKAGE_VIN_CONDITIONNE = "STOCKAGE_VIN_CONDITIONNE";
    const CHAI_ATTRIBUT_DGC = "DGC";
    const CHAI_ATTRIBUT_APPORT = "APPORT";

    const CHAI_ATTRIBUT_PRESSURAGE = "PRESSURAGE";
    const CHAI_ATTRIBUT_PRESTATAIRE = 'PRESTATAIRE';
    const CHAI_ATTRIBUT_ELEVAGE = 'ELEVAGE';

    public static $statuts = array(self::STATUT_ACTIF => 'ACTIF',
        self::STATUT_SUSPENDU => 'SUSPENDU');
    public static $regimes_crds_libelles_longs = array(self::REGIME_CRD_PERSONNALISE => 'personnalisé (P)',
        self::REGIME_CRD_COLLECTIF_ACQUITTE => 'banalisées acquittées (DA)',
        self::REGIME_CRD_COLLECTIF_SUSPENDU => 'banalisées suspendues (DS)');
   public static $regimes_crds_libelles_longs_only_suspendu = array(self::REGIME_CRD_PERSONNALISE => 'CRD personnalisées',
            self::REGIME_CRD_COLLECTIF_SUSPENDU => 'CRD collectives');
    public static $regimes_crds_libelles = array(self::REGIME_CRD_PERSONNALISE => 'Personnalisé',
        self::REGIME_CRD_COLLECTIF_ACQUITTE => 'Banalisées acquittées',
        self::REGIME_CRD_COLLECTIF_SUSPENDU => 'Banalisées suspendues');
    public static $regimes_crds_libelles_courts = array(self::REGIME_CRD_PERSONNALISE => 'P',
        self::REGIME_CRD_COLLECTIF_ACQUITTE => 'DA',
        self::REGIME_CRD_COLLECTIF_SUSPENDU => 'DS');
    public static $natures_inao_libelles = array(
        "01" => self::NATURE_INAO_PRODUCTEUR_INDIVIDUEL,
        "04" => self::NATURE_INAO_COOPERATIVE,
        "05" => self::NATURE_INAO_UNION_DE_COOPERATIVES,
        "06" => self::NATURE_SOCIETE_CIVILE,
        "07" => self::NATURE_INAO_SICA,
        "08" => self::NATURE_INAO_SOCIETE_COMMERCIALE,
        "09" => self::NATURE_INAO_AUTRE);
    public static $caution_libelles = array(self::CAUTION_DISPENSE => 'Dispensé',
        self::CAUTION_CAUTION => 'Caution');

    public static $chaisAttributsLibelles = array(self::CHAI_ATTRIBUT_VINIFICATION => 'Chai de vinification',
                                                  self::CHAI_ATTRIBUT_STOCKAGE_VRAC => 'Stockage Vin en Vrac',
                                                  self::CHAI_ATTRIBUT_STOCKAGE_VIN_CONDITIONNE => 'Stockage Vin Conditionné',
                                                  self::CHAI_ATTRIBUT_DGC => 'Dénomination Géographique complémentaire',
                                                  self::CHAI_ATTRIBUT_APPORT => 'Apport',
                                                  self::CHAI_ATTRIBUT_CONDITIONNEMENT => 'Centre de conditionnement',
                                                  self::CHAI_ATTRIBUT_PRESTATAIRE => 'Prestataire de service',
                                                  self::CHAI_ATTRIBUT_ELEVAGE => 'Elevage et vieillissement');

    public static $chaisAttributsInImport = array("Vinification" => EtablissementClient::CHAI_ATTRIBUT_VINIFICATION,
                                                  "VV Stockage" => EtablissementClient::CHAI_ATTRIBUT_STOCKAGE_VRAC,
                                                  "VC Stockage" => EtablissementClient::CHAI_ATTRIBUT_STOCKAGE_VIN_CONDITIONNE,
                                                  "DGC" => EtablissementClient::CHAI_ATTRIBUT_DGC,
                                                  "Apport" => EtablissementClient::CHAI_ATTRIBUT_APPORT,
                                                  "Conditionnement" => EtablissementClient::CHAI_ATTRIBUT_CONDITIONNEMENT,
                                                  "Prestataire" => EtablissementClient::CHAI_ATTRIBUT_PRESTATAIRE,
                                                  "Elevage" => EtablissementClient::CHAI_ATTRIBUT_ELEVAGE,
                                                    );

    public static $chaisAttributByLiaisonType = array(
                                                    self::TYPE_LIAISON_COOPERATIVE => 'Apport',
                                                    self::TYPE_LIAISON_NEGOCIANT => 'Apport',
                                                );
    public static function getInstance() {
        return acCouchdbManager::getClient("Etablissement");
    }

    public function createEtablissementFromSociete($societe, $famille = null) {
        return $societe->createEtablissement($famille);
    }

    public function getNextIdentifiantForSociete($societe) {
        $societe_id = $societe->identifiant;
        $etbs = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        $last_num = 0;
        foreach ($etbs as $id) {
            if (!preg_match('/ETABLISSEMENT-'.SocieteClient::getInstance()->getSocieteFormatIdentifiantRegexp().'([0-9]{2})/', $id, $matches)) {
                continue;
            }

            $num = $matches[3];
            if ($num > $last_num) {
                $last_num = $num;
            }
        }

        return sprintf("%s%02d", $societe_id, $last_num + 1);
    }

    public function getAtSociete($societe_id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('ETABLISSEMENT-' . $societe_id . '00')->endkey('ETABLISSEMENT-' . $societe_id . '99')->execute($hydrate);
    }

    public function getViewClient($view) {
        return acCouchdbManager::getView("etablissement", $view, 'Etablissement');
    }

    public function findAll() {
        return EtablissementRegionView::getInstance()->findAll();
    }

    public function findByFamille($famille) {
        return EtablissementRegionView::getInstance()->findByFamilleAndRegionNonSuspendu($famille);
    }

    public function findByFamillesAndRegions($familles, $regions) {
        return EtablissementRegionView::getInstance()->findByFamillesAndRegionsNonSuspendus($familles, $regions, null);
    }

    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Etablissement
     * @deprecated find()
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::find('ETABLISSEMENT-' . $id, $hydrate);
    }

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {

        return parent::find($this->getId($id_or_identifiant), $hydrate, $force_return_ls);
    }


    /**
     * Rechercher un établissment par id, identifiant, cvi, no accices, ppm
     *
     * @param string $anyIdentifiant Id, identifiant, cvi, no accices, ppm
     * @param bool $withSuspendu Inclure les établissements suspendu
     *
     * @return Etablissement
     */
    public function findAny($anyIdentifiant, $withSuspendu = false) {
        $etablissement = $this->find($this->getId($anyIdentifiant));

        if($etablissement) {

            return $etablissement;
        }

        return $this->findByCvi($anyIdentifiant, $withSuspendu);
    }

    public function findByAccises($no_accises, $with_suspendu = false) {
        return $this->findByCvi($no_accises, $with_suspendu);
    }

    public function findByCvi($cvi, $with_suspendu = false, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      return $this->findByCviOrAcciseOrPPM($cvi, $with_suspendu, $hydrate);
    }

    public function findByPPM($ppm, $with_suspendu = false) {
      return $this->findByCviOrAcciseOrPPM($ppm, $with_suspendu);
    }

    public function findByAccise($accise, $with_suspendu = false) {
      return $this->findByCviOrAcciseOrPPM($accise, $with_suspendu);
    }

    public function findByCviOrAcciseOrPPM($accise, $with_suspendu = false, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      return $this->findByCviOrAcciseOrPPMOrSirenOrTVA($accise, $with_suspendu, $hydrate);
    }
    public function findByCviOrAcciseOrPPMOrSiren($accise, $with_suspendu = false, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      return $this->findByCviOrAcciseOrPPMOrSirenOrTVA($accise, $with_suspendu, $hydrate);
    }
    public function findByCviOrAcciseOrPPMOrSirenOrTVA($cvi_or_accise_or_ppm, $with_suspendu = false, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT){

      $cvi_or_accise_or_ppm = str_replace(' ', '', $cvi_or_accise_or_ppm);

      if (!$cvi_or_accise_or_ppm) {
          return null;
      }

      $rows = EtablissementFindByCviView::getInstance()->findByCvi($cvi_or_accise_or_ppm);
      $c = count($rows);
      if ($c && $c < 20) {
        foreach ($rows as $r) {
          $e = $this->find($r->id, acCouchdbClient::HYDRATE_JSON);
          if (!$with_suspendu && $e->statut == EtablissementClient::STATUT_SUSPENDU) {
              continue;
          }
          $e = $this->find($r->id, $hydrate);
          if ($e) {
              return $e;
          }
        }
      }

      $s = SocieteClient::getInstance()->findBySiretOrTVA($cvi_or_accise_or_ppm);
      if ($s) {
          return $s->getEtablissementPrincipal();
      }

      return null;
    }

    public function getId($id_or_identifiant) {
        $id = $id_or_identifiant;
        if (strpos($id_or_identifiant, 'ETABLISSEMENT-') === false) {
            $id = 'ETABLISSEMENT-' . $id_or_identifiant;
        }

        return $id;
    }

    public function getIdentifiant($id_or_identifiant) {

        return $identifiant = str_replace('ETABLISSEMENT-', '', $id_or_identifiant);
    }

    /**
     *
     * @deprecated find()
     */
    public function findByIdentifiant($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::find('ETABLISSEMENT-' . $identifiant, $hydrate);
    }

    public function matchFamille($f) {
        if (preg_match('/producteur/i', $f)) {

            return EtablissementFamilles::FAMILLE_PRODUCTEUR;
        }
        if (preg_match('/n.*gociant/i', $f)) {

            return EtablissementFamilles::FAMILLE_NEGOCIANT;
        }
        if (preg_match('/courtier/i', $f)) {

            return EtablissementFamilles::FAMILLE_COURTIER;
        }

        throw new sfException("La famille $f doit être soit producteur soit negociant soit courtier");
    }

    public function matchSousFamille($sf) {
        $sf = KeyInflector::slugify($sf);
        $matches = array("(particuliere|cooperative)" => EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE,
            "regional" => EtablissementFamilles::SOUS_FAMILLE_REGIONAL,
            "exterieur" => EtablissementFamilles::SOUS_FAMILLE_EXTERIEUR,
            "etranger" => EtablissementFamilles::SOUS_FAMILLE_ETRANGER,
            "union" => EtablissementFamilles::SOUS_FAMILLE_UNION,
            "vinificateur" => EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR);
        foreach ($matches as $match => $s) {
            if (preg_match('/' . $match . '/i', $sf)) {
                return $s;
            }
        }

        if (!$sf) {
            return EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
        }

        throw new sfException('Sous Famille "' . $sf . '" inconnue');
    }

    public static function getStatuts() {
        return array(self::STATUT_ACTIF => self::STATUT_ACTIF,
            self::STATUT_SUSPENDU => self::STATUT_SUSPENDU);
    }

    public static function getRecettesLocales() {
        return array(self::RECETTE_LOCALE => 'Recette locale');
    }

    public static function getRegionsWithoutHorsInterLoire() {
        return array(self::REGION_CVO => self::REGION_CVO);
    }

    public static function getRegions() {
    	return sfConfig::get('app_donnees_viticoles_regions', array());
    }

    public static function getNaturesInao() {
        return array_merge(array('' => ''), self::$natures_inao_libelles);
    }

    public function getNatureInaoLibelle($nature) {
        if (!$nature) {
            return "";
        }
        $naturesLibelles = self::getNaturesInao();
        return $naturesLibelles[$nature];
    }

    public static function getTypeDR() {
        return array(self::TYPE_DR_DRM => self::TYPE_DR_DRM,
            self::TYPE_DR_DRA => self::TYPE_DR_DRA);
    }

    public static function getTypesLiaisons() {
        return array(

            self::TYPE_LIAISON_BAILLEUR => 'A pour bailleur',
            self::TYPE_LIAISON_METAYER => 'A pour métayer',

            self::TYPE_LIAISON_FERMIER => 'A pour fermier',

            self::TYPE_LIAISON_COOPERATIVE => 'A pour coopérative',
            self::TYPE_LIAISON_COOPERATEUR => 'A pour coopérateur',

            self::TYPE_LIAISON_NEGOCIANT => 'A pour négociant (vin en vrac)',
            self::TYPE_LIAISON_VENDEUR_VRAC => 'A pour vendeur de vin en vrac',

            self::TYPE_LIAISON_NEGOCIANT_VINIFICATEUR => 'A pour négociant vinificateur',
            self::TYPE_LIAISON_APPORTEUR_RAISIN => 'A pour apporteur de raisins',

            self::TYPE_LIAISON_HEBERGE_TIERS => 'Hébergé chez un tiers',
            self::TYPE_LIAISON_HEBERGE => 'Héberge',

            self::TYPE_LIAISON_LABO => 'A pour labo',
            self::TYPE_LIAISON_ANALYSE_DE => "Analyse les vins de",


        );
    }

    public static function getTypesLiaisonsOrganisation() {

        return array(
            self::TYPE_LIAISON_BAILLEUR => self::TYPE_LIAISON_METAYER,
            self::TYPE_LIAISON_COOPERATEUR => self::TYPE_LIAISON_COOPERATIVE,
            self::TYPE_LIAISON_VENDEUR_VRAC => self::TYPE_LIAISON_NEGOCIANT,
            self::TYPE_LIAISON_APPORTEUR_RAISIN => self::TYPE_LIAISON_NEGOCIANT_VINIFICATEUR,
            self::TYPE_LIAISON_HEBERGE => self::TYPE_LIAISON_HEBERGE_TIERS,
            self::TYPE_LIAISON_LABO => self::TYPE_LIAISON_ANALYSE_DE,
        );
    }

    public static function isTypeLiaisonCanHaveChai($typeLiaison) {

        return array_key_exists($typeLiaison, array_flip(self::getTypesLiaisonsOrganisation()));
    }

    public static function getTypeLiaisonOpposee($typeLiaison) {
        $typeLiaisonsOrganisation = self::getTypesLiaisonsOrganisation();
        $typeLiaisonsOrganisationInverse = array_flip($typeLiaisonsOrganisation);

        if (isset($typeLiaisonsOrganisation[$typeLiaison])) {

            return $typeLiaisonsOrganisation[$typeLiaison];
        }

        if (isset($typeLiaisonsOrganisationInverse[$typeLiaison])) {

            return $typeLiaisonsOrganisationInverse[$typeLiaison];
        }

        return null;
    }

    public static function getPrefixForRegion($region) {
        $prefixs = array(self::REGION_CVO => '1');
        return $prefixs[$region];
    }

    public function buildInfosContact($etb) {
        $result = new stdClass();
        $region = $etb->region;
        $contacts = sfConfig::get('app_teledeclaration_contact_contrat');

        if ($etb->famille == EtablissementFamilles::FAMILLE_COURTIER) {
            $region = self::REGION_HORS_CVO;

            $result->nom = $contacts[$region]['nom'];
            $result->email = $contacts[$region]['email'];
            $result->telephone = $contacts[$region]['telephone'];
            return $result;
        }
        $result->nom = $contacts[$region]['nom'];
        $result->email = $contacts[$region]['email'];
        $result->telephone = $contacts[$region]['telephone'];
        return $result;
    }

    public function calculRegion($etablissement) {
        if($etablissement->getPays() != 'FR') {

            return self::REGION_HORS_CVO;
        }

        if(!preg_match("/".VracConfiguration::getInstance()->getRegionDepartement()."/", $etablissement->getCodePostal())) {

            return self::REGION_HORS_CVO;
        }

        return self::REGION_CVO;
    }

    public static function cleanCivilite($nom) {
        return preg_replace("/^(M|MME|EARL|SCEA|SARL|SDF|GAEC|MLLE|SA|SAS|Mme|M\.|STEF|MEMR|MM|IND|EURL|SCA|EI|SCI|MMES|SASU|SC|SCV|Melle|ASSO|GFA)[,]? /", "", $nom);
    }
}
