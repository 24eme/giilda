<?php

class EtablissementClient extends acCouchdbClient {

    /**
     *
     * @return EtablissementClient
     */
    const REGION_TOURS = 'TOURS';
    const REGION_ANGERS = 'ANGERS';
    const REGION_NANTES = 'NANTES';
    const REGION_HORSINTERLOIRE = 'HORS_INTERLOIRE';

    const REGION_CENTRE_AOP = 'CENTRE_AOP';
    const REGION_CENTRE_IGP = 'CENTRE_IGP';
    const REGION_PDL_AOP = 'PDL_AOP';
    const REGION_PDL_IGP = 'PDL_IGP';
    const REGION_HORS_REGION = 'HORS_REGION';

    const RECETTE_LOCALE = 'RECETTE_LOCALE';
    const TYPE_DR_DRM = 'DRM';
    const TYPE_DR_DRA = 'DRA';
    const TYPE_LIAISON_BAILLEUR = 'BAILLEUR';
    const TYPE_LIAISON_METAYER = 'METAYER';
    const TYPE_LIAISON_ADHERENT = 'ADHERENT'; //pour les cooperateurs
    const TYPE_LIAISON_CONTRAT_INTERNE = 'CONTRAT_INTERNE';
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
    const REGIME_CRD_COLLECTIF_ACQUITTE_SUSPENDU = 'COLLECTIFACQUITTE,COLLECTIFSUSPENDU';
    const REGIME_CRD_COLLECTIF_PERSONNALISE_SUSPENDU = 'PERSONNALISE,COLLECTIFSUSPENDU';

    const CAUTION_DISPENSE = 'DISPENSE';
    const CAUTION_CAUTION = 'CAUTION';

    public static $statuts = array(self::STATUT_ACTIF => 'ACTIF',
        self::STATUT_SUSPENDU => 'SUSPENDU');
    public static $regimes_crds_libelles_longs = array(
        self::REGIME_CRD_COLLECTIF_SUSPENDU => 'collectif suspendu (DS)',
        self::REGIME_CRD_COLLECTIF_ACQUITTE => 'collectif acquitté (DA)',
        self::REGIME_CRD_COLLECTIF_ACQUITTE_SUSPENDU => 'collectif acquitté + collectif suspendu (DA+DS)',
        self::REGIME_CRD_COLLECTIF_PERSONNALISE_SUSPENDU => 'personnalisé + collectif suspendu (P+DS)',
        self::REGIME_CRD_PERSONNALISE => 'personnalisé (P)',
    );
    public static $regimes_crds_libelles = array(
        self::REGIME_CRD_COLLECTIF_SUSPENDU => 'Collectif suspendu',
        self::REGIME_CRD_COLLECTIF_ACQUITTE => 'Collectif acquitté',
        self::REGIME_CRD_COLLECTIF_ACQUITTE_SUSPENDU => 'Collectif acquitté + Collectif suspendu',
        self::REGIME_CRD_COLLECTIF_PERSONNALISE_SUSPENDU => 'Personnalisé + Collectif suspendu',
        self::REGIME_CRD_PERSONNALISE => 'Personnalisé'
    );
    public static $regimes_crds_libelles_courts = array(
        self::REGIME_CRD_COLLECTIF_SUSPENDU => 'C-DS',
        self::REGIME_CRD_COLLECTIF_ACQUITTE => 'C-DA',
        self::REGIME_CRD_COLLECTIF_ACQUITTE_SUSPENDU => 'C-DA+C-DS',
        self::REGIME_CRD_COLLECTIF_PERSONNALISE_SUSPENDU => 'P+C-DS',
        self::REGIME_CRD_PERSONNALISE => 'P',
    );

    public static $caution_libelles = array(self::CAUTION_DISPENSE => 'Dispensé',
        self::CAUTION_CAUTION => 'Caution');

    public static function getInstance() {
        return acCouchdbManager::getClient("Etablissement");
    }

    public function createEtablissementFromSociete($societe) {
        $etablissement = new Etablissement();
        $etablissement->id_societe = $societe->_id;
        $etablissement->identifiant = $this->getNextIdentifiantForSociete($societe);
        $famillesSocieteTypes = self::getFamillesSocieteTypesArray();
        $etablissement->famille = $famillesSocieteTypes[$societe->type_societe];
        $etablissement->constructId();
        return $etablissement;
    }

    public function getNextIdentifiantForSociete($societe) {
        $societe_id = $societe->identifiant;
        $etbs = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        $last_num = 0;
        foreach ($etbs as $id) {
            if (!preg_match('/ETABLISSEMENT-[0-9]{6}([0-9]{2})/', $id, $matches)) {
                continue;
            }

            $num = $matches[1];
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

    public function findByCvi($cvi,$withSuspendu = true) {
        $rows = EtablissementFindByCviView::getInstance()->findByCvi($cvi);
        if (!count($rows)) {
            return null;
        }
        if(!$withSuspendu){
          foreach ($rows as $row) {
            $etb = $this->find($row->id);
            if(!$etb->isActif()){
              continue;
            }
          }
          return $etb;
        }

        return $this->find($rows[0]->id);
    }

    public function findAllByCvi($cvi) {
        $rows = EtablissementFindByCviView::getInstance()->findByCvi($cvi);
        if (!count($rows)) {
            return array();
        }
        $etbs = array();
        foreach ($rows as $row) {
            $etbs[$row->id] = $this->find($row->id);
        }
        return $etbs;
    }

    public function findByNoAccise($accise,$withSuspendu = true) {
        $rows = EtablissementFindByCviView::getInstance()->findByAccise($accise);

        if (!count($rows)) {
            return null;
        }
        if(!$withSuspendu){
          foreach ($rows as $row) {
            $etb = $this->find($row->id);
            if($etb->isActif()){
              return $etb;
            }
          }
          return null;
        }

        return $this->find($rows[0]->id);
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

    public static function getFamillesSocieteTypesArray() {
        return array(SocieteClient::SUB_TYPE_VITICULTEUR => EtablissementFamilles::FAMILLE_PRODUCTEUR,
            SocieteClient::SUB_TYPE_NEGOCIANT => EtablissementFamilles::FAMILLE_NEGOCIANT,
            SocieteClient::SUB_TYPE_NEGOCIANT_PUR => EtablissementFamilles::FAMILLE_NEGOCIANT_PUR,
            SocieteClient::SUB_TYPE_COURTIER => EtablissementFamilles::FAMILLE_COURTIER);
    }

    public static function getStatuts() {
        return array(self::STATUT_ACTIF => self::STATUT_ACTIF,
            self::STATUT_SUSPENDU => self::STATUT_SUSPENDU);
    }

    public static function getRecettesLocales() {
        return array(self::RECETTE_LOCALE => 'Recette locale');
    }

    public static function getRegionsWithoutHorsInterLoire($with_old_region = false) {
      $regions = array(self::REGION_PDL_AOP => self::REGION_PDL_AOP,
          self::REGION_PDL_IGP => self::REGION_PDL_IGP,
          self::REGION_CENTRE_IGP => self::REGION_CENTRE_IGP,
          self::REGION_CENTRE_AOP => self::REGION_CENTRE_AOP);
          if($with_old_region){
            $old_regions = array(self::REGION_ANGERS => self::REGION_ANGERS,
            self::REGION_TOURS => self::REGION_TOURS,
            self::REGION_NANTES => self::REGION_NANTES);
            $regions = array_merge($regions,$old_regions);
          }
        return $regions;
    }

    public static function getRegions() {
        return array_merge(self::getRegionsWithoutHorsInterLoire(), array(self::REGION_HORS_REGION => self::REGION_HORS_REGION));
    }

    public static function getTypeDR() {
        return array(self::TYPE_DR_DRM => self::TYPE_DR_DRM,
            self::TYPE_DR_DRA => self::TYPE_DR_DRA);
    }

    public static function listTypeLiaisons() {
        return array_keys(self::getTypesLiaisons());
    }

    public static function getTypesLiaisons() {
        return array(self::TYPE_LIAISON_BAILLEUR => 'A pour bailleur',
            self::TYPE_LIAISON_METAYER => 'A pour métayer',
            self::TYPE_LIAISON_ADHERENT => 'Adhérent de (coop.)',
            self::TYPE_LIAISON_CONTRAT_INTERNE => 'Contrat interne');
    }

    public static function getPrefixForRegion($region) {
        $prefixs = array(
            self::REGION_TOURS => '1',
            self::REGION_ANGERS => '2',
            self::REGION_NANTES => '3',
            self::REGION_CENTRE_AOP => '1',
            self::REGION_CENTRE_IGP => '1',
            self::REGION_PDL_IGP => '3',
            self::REGION_PDL_AOP => '3'
          );
        return $prefixs[$region];
    }

    public function buildInfosContact($etb) {
        $result = new stdClass();
        $region = $etb->region;
        $contacts = sfConfig::get('app_teledeclaration_contact_contrat');

        if ($etb->famille == SocieteClient::SUB_TYPE_COURTIER) {
            $code_postal = $etb->siege->code_postal;
            if ($code_postal && substr($code_postal, 0, 2) == "44") {
                $region = self::REGION_NANTES;
            }
            if ($code_postal && substr($code_postal, 0, 2) == "49") {
                $region = self::REGION_ANGERS;
            }
            if ($code_postal && substr($code_postal, 0, 2) == "37") {
                $region = self::REGION_TOURS;
            }
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

}
