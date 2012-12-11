<?php

class EtablissementClient extends acCouchdbClient {

    /**
     *
     * @return EtablissementClient
     */
    
    
    const REGION_TOURS = 'tours';
    const REGION_ANGERS = 'angers';
    const REGION_NANTES = 'nantes';
    const REGION_HORSINTERLOIRE = 'hors interloire';
    
    const RECETTE_LOCALE = '(recette_locale)';
    
    const STATUT_ACTIF = 'actif';
    const STATUT_SUSPENDU = 'suspendu';
    
    const TYPE_DR_DRM = 'DRM';
    const TYPE_DR_DRA = 'DRA';
    
    const TYPE_LIAISON_BAILLEUR = 'bailleur';
    const TYPE_LIAISON_METAYER = 'metayer';
    const TYPE_LIAISON_ADHERENT = 'adherent';
    const TYPE_LIAISON_CONTRAT_INTERNE = 'contrat_interne';
    
    public static function getInstance() {
        return acCouchdbManager::getClient("Etablissement");
    }

    public function createEtablissement($societe) {
        $etablissement = new Etablissement();
        $etablissement->id_societe = $societe->_id;
	$etablissement->nom = $societe->raison_sociale;
        $etablissement->identifiant = $this->getNextIdentifiantForSociete($societe);
        $famillesSocieteTypes = self::getFamillesSocieteTypesArray();
        $etablissement->famille = $famillesSocieteTypes[$societe->type_societe];
        $etablissement->constructId();
        $etablissement->save(); //
        return $etablissement;
    }

    public function getNextIdentifiantForSociete($societe) {
        $id = '';
	$societe_id = $societe->identifiant;
        $etbs = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($etbs) > 0) {
            $id .= $societe_id . sprintf("%1$02d", ((double) str_replace('ETABLISSEMENT-', '', count($etbs)) + 1));
        } else {
            $id.= $societe_id . '01';
        }
        return $id;
    }

    public function getAtSociete($societe_id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('ETABLISSEMENT-' . $societe_id . '00')->endkey('ETABLISSEMENT-' . $societe_id . '99')->execute($hydrate);
    }

    public function getViewClient($view) {
        return acCouchdbManager::getView("etablissement", $view, 'Etablissement');
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

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT) {
        return parent::find($this->getId($id_or_identifiant), $hydrate);
    }

    public function findByCvi($cvi) {
        $rows = EtablissementFindByCviView::getInstance()->findByCvi($cvi);

        if (!count($rows)) {
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

    public function retrieveOrCreateById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $etab = parent::find('ETABLISSEMENT-' . $id, $hydrate);

        if (!$etab) {
            $etab = new Etablissement();
            $etab->_id = 'ETABLISSEMENT-' . $id;
        }

        return $etab;
    }

    public function findByFamille($famille, $limit = 100) {
        if ($limit == null) {
            return $this->startkey(array($famille))
                            ->endkey(array($famille, array()))->getView('etablissement', 'tous');
        }
        return $this->startkey(array($famille))
                        ->endkey(array($famille, array()))->limit($limit)->getView('etablissement', 'tous');
    }

    public function findAll() {

        return $this->limit(100)->getView('etablissement', 'tous');
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
        array(SocieteClient::SUB_TYPE_VITICULTEUR => EtablissementFamilles::FAMILLE_PRODUCTEUR,
            SocieteClient::SUB_TYPE_NEGOCIANT => EtablissementFamilles::FAMILLE_NEGOCIANT,
            SocieteClient::SUB_TYPE_COURTIER => EtablissementFamilles::FAMILLE_COURTIER);
    }

    public static function getStatuts() {
        return array(self::STATUT_ACTIF => self::STATUT_ACTIF,
            self::STATUT_SUSPENDU => self::STATUT_SUSPENDU);
    }

    public static function getRecettesLocales() {
        return array(self::RECETTE_LOCALE => self::RECETTE_LOCALE);
    }
    
    public static function getRegionsWithoutHorsInterLoire() {
        return array(self::REGION_TOURS => self::REGION_TOURS,
            self::REGION_ANGERS => self::REGION_ANGERS,
            self::REGION_NANTES => self::REGION_NANTES);        
    }


    public static function getRegions() {
        return array_merge(self::getRegionsWithoutHorsInterLoire(),
                array(self::REGION_HORSINTERLOIRE => self::REGION_HORSINTERLOIRE));
    }

    public static function getTypeDR() {
        return array(self::TYPE_DR_DRM => self::TYPE_DR_DRM,
            self::TYPE_DR_DRA => self::TYPE_DR_DRA);
    }
    
    public static function getTypesLiaisons() {
        return array(self::TYPE_LIAISON_BAILLEUR => 'Bailleur de',
            self::TYPE_LIAISON_METAYER => 'Métayer de',
            self::TYPE_LIAISON_ADHERENT => 'Adhérent de (coop.)',
            self::TYPE_LIAISON_CONTRAT_INTERNE => 'Contrat interne');
    }    
    

}
