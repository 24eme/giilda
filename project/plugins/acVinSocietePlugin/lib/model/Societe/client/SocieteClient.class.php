<?php

class SocieteClient extends acCouchdbClient {

    const TYPE_OPERATEUR = 'OPERATEUR';
    const SUB_TYPE_VITICULTEUR = 'VITICULTEUR';
    const SUB_TYPE_COOPERATIVE = 'COOPERATIVE';
    const SUB_TYPE_NEGOCIANT = 'NEGOCIANT';
    const SUB_TYPE_COURTIER = 'COURTIER';
    const TYPE_PRESSE = 'PRESSE';
    const TYPE_PARTENAIRE = 'PARTENAIRE';
    const SUB_TYPE_DOUANE = 'DOUANE';
    const SUB_TYPE_INSTITUTION = 'INSTITUTION';
    const SUB_TYPE_HOTELRESTAURANT = 'HOTEL-RESTAURANT';
    const SUB_TYPE_SYNDICAT = 'SYNDICAT';
    const SUB_TYPE_AUTRE = 'AUTRE';
    const STATUT_ACTIF = 'ACTIF';
    const STATUT_SUSPENDU = 'SUSPENDU';
    const STATUT_EN_CREATION = 'EN_CREATION';
    const NUMEROCOMPTE_TYPE_CLIENT = 'CLIENT';
    const NUMEROCOMPTE_TYPE_FOURNISSEUR = 'FOURNISSEUR';
    const FOURNISSEUR_TYPE_MDV = "MDV";
    const FOURNISSEUR_TYPE_PLV = "PLV";
    const FOURNISSEUR_TYPE_FOURNISSEUR = "FOURNISSEUR";

    public static function getInstance() {
        return acCouchdbManager::getClient("Societe");
    }

    public function getId($identifiant) {
        if (preg_match('/^SOCIETE/', $identifiant))
            return $identifiant;
        return 'SOCIETE-' . $identifiant;
    }

    public function getIdentifiant($id_or_identifiant) {
        return $identifiant = str_replace('SOCIETE-', '', $id_or_identifiant);
    }

    public function findBySiret($siret) {
        $index = acElasticaManager::getType('Societe');
        $elasticaQueryString = new acElasticaQueryQueryString();
        $elasticaQueryString->setDefaultOperator('AND');
        $elasticaQueryString->setQuery(sprintf("siret:%s", $siret));

        $q = new acElasticaQuery();
        $q->setQuery($elasticaQueryString);
        $q->setLimit(1);

        $res = $index->search($q);

        foreach ($res->getResults() as $er) {
            $r = $er->getData();

            return $this->find($r['_id']);
        }

        return null;
    }

    public function getSocietesWithStatut($statut) {
        return array_reverse(SocieteAllView::getInstance()->findByInterproAndStatut('INTERPRO-inter-loire', $statut));
    }

    public function getSocietesWithTypeAndRaisonSociale($type, $raison_sociale) {
        return SocieteAllView::getInstance()->findByInterproAndStatut('INTERPRO-inter-loire', null, array($type), $raison_sociale);
    }

    public function createSociete($raison_sociale, $type) {
        $societe = new Societe();
        $societe->raison_sociale = $raison_sociale;
        $societe->type_societe = $type;
        $societe->interpro = 'INTERPRO-inter-loire';
        $societe->identifiant = $this->getNextIdentifiantSociete();
        $societe->statut = SocieteClient::STATUT_EN_CREATION;
        $societe->cooperative = 0;
        $societe->add("date_creation", date('Y-m-d'));
        $societe->constructId();

        return $societe;
    }

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {
        if (preg_match('/^SOCIETE[-]{1}[0-9]*$/', $id_or_identifiant)) {

            return parent::find($id_or_identifiant, $hydrate, $force_return_ls);
        }

        return parent::find($this->getId($id_or_identifiant), $hydrate, $force_return_ls);
    }

    public function getNextIdentifiantSociete() {
        $id = '';
        $societes = $this->getSocietesIdentifiants(acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();

        $last_num = 0;
        foreach ($societes as $id) {
            if (!preg_match('/SOCIETE-8([0-9]{5})/', $id, $matches)) {
                continue;
            }

            $num = $matches[1];
            if ($num > $last_num) {
                $last_num = $num;
            }
        }

        return sprintf("8%05d", $last_num + 1);
    }

    public function getNextCodeFournisseur() {
        $societes = SocieteExportView::getInstance()->findByInterpro('INTERPRO-inter-loire');
        $nextCF = 0;
        foreach ($societes as $societe) {
            if ($cf = $societe->value[SocieteExportView::VALUE_CODE_COMPTABLE_FOURNISSEUR]) {
                if (substr($cf, 1) > $nextCF) {
                    $nextCF = substr($cf, 1);
                }
            }
        }
        return 'F' . sprintf("%1$07d", ((double) ($nextCF + 1)));
    }

    public function getSocietesIdentifiants($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('SOCIETE-800000')->endkey('SOCIETE-999999')->execute($hydrate);
    }

    public function findByIdentifiantSociete($identifiant) {
        return $this->find($this->getId($identifiant));
    }

    public function getInterlocuteursWithOrdre($identifiant, $withSuspendus) {
        $contactsArr = $this->findByIdentifiantSociete($identifiant)->getInterlocuteursWithOrdre();
        $result = array();
        foreach ($contactsArr as $id => $value) {
            $compte = CompteClient::getInstance()->find($id);
            if ($withSuspendus) {
                $result[] = $compte;
            } else {

                if ($compte->statut != SocieteClient::STATUT_SUSPENDU) {
                    $result[] = $compte;
                }
            }
        }
        return $result;
    }

    public static function getSocieteTypes() {
        return array(self::TYPE_OPERATEUR => array(self::SUB_TYPE_VITICULTEUR => self::SUB_TYPE_VITICULTEUR,
                self::SUB_TYPE_NEGOCIANT => self::SUB_TYPE_NEGOCIANT,
                self::SUB_TYPE_COURTIER => self::SUB_TYPE_COURTIER),
            self::TYPE_PRESSE => self::TYPE_PRESSE,
            self::TYPE_PARTENAIRE => array(
                self::SUB_TYPE_DOUANE => self::SUB_TYPE_DOUANE,
                self::SUB_TYPE_INSTITUTION => self::SUB_TYPE_INSTITUTION,
                self::SUB_TYPE_HOTELRESTAURANT => self::SUB_TYPE_HOTELRESTAURANT,
                self::SUB_TYPE_SYNDICAT => self::SUB_TYPE_SYNDICAT,
                self::SUB_TYPE_AUTRE => self::SUB_TYPE_AUTRE));
    }

    public static function getStatuts() {
        return array(self::STATUT_ACTIF => 'Actif', self::STATUT_SUSPENDU => 'Suspendu');
    }

//    public static function getTypesNumeroCompte() {
//        return array(self::NUMEROCOMPTE_TYPE_CLIENT => 'Client', self::NUMEROCOMPTE_TYPE_FOURNISSEUR => 'Fournisseur');
//    }

    public static function getSocieteTypesWithChais() {
        return array(self::SUB_TYPE_VITICULTEUR => self::SUB_TYPE_VITICULTEUR,
            self::SUB_TYPE_COOPERATIVE => self::SUB_TYPE_COOPERATIVE,
            self::SUB_TYPE_NEGOCIANT => self::SUB_TYPE_NEGOCIANT,
            self::SUB_TYPE_COURTIER => self::SUB_TYPE_COURTIER);
    }

    public function addTagRgtEnAttenteFromFile($path, $societesCodeClientView) {
        $file = fopen($path, 'r');

        $nb_ligne = 1;
        $resultArr = array();
        while ($csv_arr = fgetcsv($file, 0, ';')) {
            if (!isset($csv_arr[1])) {
                throw new sfException("Le csv est mal formatté : la ligne $nb_ligne doit possèder un identifiant en deuxième valeur");
            }
            $code_comptable_client_csv = sprintf("%08d", $csv_arr[1]);
            if (!preg_match('/^[0-9]{8}$/', $code_comptable_client_csv)) {
                throw new sfException("Le code comptable client $code_comptable_client_csv de la ligne $nb_ligne est mal formatté");
            }

            $societe = null;
            foreach ($societesCodeClientView as $societeView) {

                if (!array_key_exists('ligne_' . $nb_ligne, $resultArr)) {
                    $resultArr['ligne_' . $nb_ligne] = array();
                }

                if ($societe) {
                    break;
                }

                $code_comptable_client_view = $societeView->value[SocieteExportView::VALUE_CODE_COMPTABLE_CLIENT];

                if ($code_comptable_client_view != $code_comptable_client_csv) {
                    continue;
                }

                $identifiant = $societeView->key[SocieteExportView::KEY_IDENTIFIANT];
                $societe = $this->find($identifiant);

                if (!$societe) {
                    $resultArr['ligne_' . $nb_ligne]['msg'] = "La société d'identifiant $identifiant n'a pas été trouvé";
                    $resultArr['ligne_' . $nb_ligne]['type'] = "ERREUR";
                    continue;
                }

                    $compte = $societe->getMasterCompte();
                try {
                    $compte->addTag("manuel", "rgt_en_attente");
                    $resultArr['ligne_' . $nb_ligne]['msg'] = "Ajout du Tag RgtEnAttente pour compte $compte->identifiant";
                    $resultArr['ligne_' . $nb_ligne]['type'] = "VALIDE";
                    $compte->save();
                } catch (sfException $e) {
                    $resultArr['ligne_' . $nb_ligne]['msg'] = "ERREUR problème d'enregistrement du tag pour le compte $compte->identifiant";
                    $resultArr['ligne_' . $nb_ligne]['type'] = "ERREUR";
                }
            }
            $nb_ligne++;
        }
        fclose($file);
        return $resultArr;
    }

}
