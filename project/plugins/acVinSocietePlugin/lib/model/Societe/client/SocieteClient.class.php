<?php

class SocieteClient extends acCouchdbClient {

    const TYPE_OPERATEUR = 'operateur';
    const SUB_TYPE_VITICULTEUR = 'viticulteur';
    const SUB_TYPE_NEGOCIANT = 'negociant';
    const SUB_TYPE_COURTIER = 'courtier';
    const TYPE_PRESSE = 'presse';
    const TYPE_PARTENAIRE = 'partenaire';
    const SUB_TYPE_DOUANE = 'douane';
    const SUB_TYPE_INSTITUTION = 'institution';
    const SUB_TYPE_HOTELRESTAURANT = 'hotel/restaurant';
    const SUB_TYPE_AUTRE = 'autre';
    
    
    const STATUT_ACTIF = 'actif';
    const STATUT_SUSPENDU = 'suspendu';
    
    const NUMEROCOMPTE_TYPE_CLIENT = 'client';    
    const NUMEROCOMPTE_TYPE_FOURNISSEUR = 'fournisseur';

    public static function getInstance() {
        return acCouchdbManager::getClient("Societe");
    }

    public function getId($identifiant) {
	if (preg_match('/^SOCIETE/', $identifiant))
		return $identifiant;
        return 'SOCIETE-' . $identifiant;
    }

    public function createSociete($raison_sociale, $type) {
        $societe = new Societe();
        $societe->raison_sociale = $raison_sociale;
        $societe->type_societe = $type;
        $societe->interpro = 'INTERPRO-inter-loire';
        $societe->identifiant = $this->getNextIdentifiantSociete();
        $societe->statut = SocieteClient::STATUT_ACTIF;
        $societe->cooperative = 0;
        $societe->constructId();
	$societe->save();
	$compte = $societe->createCompteSociete();
	$compte->save();
	EtablissementClient::getInstance()->createEtablissement($societe);
        return $societe;
    }

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT) {
        if(preg_match('/^SOCIETE[-]{1}[0-9]*$/', $id_or_identifiant)) return parent::find($id_or_identifiant, $hydrate);
        return parent::find($this->getId($id_or_identifiant), $hydrate);
    }

    public function getNextIdentifiantSociete() {
        $id = '';
        $societes = self::getSocietesIdentifiants(acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($societes) > 0) {
            $id .= '8'.sprintf("%1$05d",((double) str_replace('SOCIETE-8', '', count($societes)) + 1));
        } else {
            $id.= '800001';
        }
        return $id;
    }
    
    public function getSocietesIdentifiants($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('SOCIETE-800000')->endkey('SOCIETE-999999')->execute($hydrate);
    }

    public function findByIdentifiantSociete($identifiant) {
        return $this->find($this->getId($identifiant));
    }

    public static function getSocieteTypes() {
        return array(self::TYPE_OPERATEUR => array(self::SUB_TYPE_VITICULTEUR => self::SUB_TYPE_VITICULTEUR,
                self::SUB_TYPE_NEGOCIANT => self::SUB_TYPE_NEGOCIANT,
                self::SUB_TYPE_COURTIER => self::SUB_TYPE_COURTIER),
            self::TYPE_PRESSE => self::TYPE_PRESSE,
            self::TYPE_PARTENAIRE => array(self::SUB_TYPE_INSTITUTION => self::SUB_TYPE_INSTITUTION,
                self::SUB_TYPE_HOTELRESTAURANT => self::SUB_TYPE_HOTELRESTAURANT,
                self::SUB_TYPE_AUTRE => self::SUB_TYPE_AUTRE));
    }

    public static function getStatuts() {
        return array(self::STATUT_ACTIF => 'Actif', self::STATUT_SUSPENDU => 'Suspendu');
    }
    
    public static function getTypesNumeroCompte() {
        return array(self::NUMEROCOMPTE_TYPE_CLIENT => 'Client', self::NUMEROCOMPTE_TYPE_FOURNISSEUR => 'Fournisseur');
    }
    
    public static function getSocieteTypesWithChais() {
        return array(self::SUB_TYPE_VITICULTEUR => self::SUB_TYPE_VITICULTEUR,
                    self::SUB_TYPE_NEGOCIANT => self::SUB_TYPE_NEGOCIANT,
                    self::SUB_TYPE_COURTIER => self::SUB_TYPE_COURTIER);
    }
    
}
