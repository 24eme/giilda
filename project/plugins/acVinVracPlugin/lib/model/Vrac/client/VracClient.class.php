<?php

class VracClient extends acCouchdbClient {

    const VRAC_VIEW_CAMPAGNE = 0;
    const VRAC_VIEW_STATUT = 1;
    const VRAC_VIEW_ID = 2;
    const VRAC_VIEW_NUMCONTRAT = 3;
    const VRAC_VIEW_NUMARCHIVE = 4;
    const VRAC_VIEW_ACHETEUR_ID = 5;
    const VRAC_VIEW_ACHETEUR_NOM = 6;
    const VRAC_VIEW_VENDEUR_ID = 7;
    const VRAC_VIEW_VENDEUR_NOM = 8;
    const VRAC_VIEW_MANDATAIRE_ID = 9;
    const VRAC_VIEW_MANDATAIRE_NOM = 10;
    const VRAC_VIEW_TYPEPRODUIT = 11;
    const VRAC_VIEW_PRODUIT_ID = 12;
    const VRAC_VIEW_PRODUIT_LIBELLE = 13;
    const VRAC_VIEW_VOLPROP = 14;
    const VRAC_VIEW_VOLENLEVE = 15;
    const VRAC_VIEW_CREATEURIDENTIFANT = 16;
    const VRAC_VIEW_SIGNATUREVENDEUR = 17;
    const VRAC_VIEW_SIGNATUREACHETEUR = 18;
    const VRAC_VIEW_SIGNATURECOURTIER = 19;
    const VRAC_VIEW_BOUTEILLE_QUANTITE = 20;
    const VRAC_VIEW_JUS_QUANTITE = 21;
    const VRAC_VIEW_RAISIN_QUANTITE = 22;
    const VRAC_VIEW_PRIX_UNITAIRE = 23;
    const VRAC_SIMILAIRE_KEY_VENDEURID = 0;
    const VRAC_SIMILAIRE_KEY_ACHETEURID = 1;
    const VRAC_SIMILAIRE_KEY_MANDATAIREID = 3;
    const VRAC_SIMILAIRE_KEY_TYPE = 4;
    const VRAC_SIMILAIRE_KEY_PRODUIT = 5;
    const VRAC_SIMILAIRE_KEY_VOLPROP = 6;
    const VRAC_SIMILAIRE_VALUE_NUMCONTRAT = 0;
    const VRAC_SIMILAIRE_VALUE_STATUT = 1;
    const VRAC_SIMILAIRE_VALUE_MILLESIME = 2;
    const VRAC_SIMILAIRE_VALUE_VOLPROP = 3;
    const VRAC_SIMILAIRE_VALUE_NUMARCHIVE = 4;
    const TYPE_TRANSACTION_RAISINS = 'RAISINS';
    const TYPE_TRANSACTION_MOUTS = 'MOUTS';
    const TYPE_TRANSACTION_VIN_VRAC = 'VIN_VRAC';
    const TYPE_TRANSACTION_VIN_BOUTEILLE = 'VIN_BOUTEILLE';
    const TYPE_CONTRAT_SPOT = 'SPOT';
    const TYPE_CONTRAT_PLURIANNUEL = 'PLURIANNUEL';
    const CVO_NATURE_MARCHE_DEFINITIF = 'MARCHE_DEFINITIF';
    const CVO_NATURE_COMPENSATION = 'COMPENSATION';
    const CVO_NATURE_NON_FINANCIERE = 'NON_FINANCIERE';
    const CVO_NATURE_VINAIGRERIE = 'VINAIGRERIE';
    const CATEGORIE_VIN_GENERIQUE = 'GENERIQUE';
    const CATEGORIE_VIN_DOMAINE = 'DOMAINE';
    const CVO_REPARTITION_50_50 = '50';
    const CVO_REPARTITION_100_VITI = '100';
    const CVO_REPARTITION_100_NEGO = '100_ACHETEUR';
    const CVO_REPARTITION_0_VINAIGRERIE = '0';
    const RESULTAT_LIMIT = 1500;
    const STATUS_CONTRAT_BROUILLON = 'BROUILLON';
    const STATUS_CONTRAT_ATTENTE_SIGNATURE = 'ATTENTE_SIGNATURE';
    const STATUS_CONTRAT_VISE = 'VISE';
    const STATUS_CONTRAT_VALIDE = 'VALIDE';
    const STATUS_CONTRAT_SOLDE = 'SOLDE';
    const STATUS_CONTRAT_ANNULE = 'ANNULE';
    const STATUS_CONTRAT_NONSOLDE = 'NONSOLDE';
    const STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI = "ATTENTE_SIGNATURE_MOI";
    const STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES = "ATTENTE_SIGNATURE_AUTRES";

    const LABEL_AGRICULTURE_BIOLOGIQUE = 'agriculture_biologique';

    public static $types_transaction = array(VracClient::TYPE_TRANSACTION_RAISINS => 'Raisins',
        VracClient::TYPE_TRANSACTION_MOUTS => 'Moûts',
        VracClient::TYPE_TRANSACTION_VIN_VRAC => 'Vin en vrac',
        VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE => 'Vin conditionné');
    public static $categories_vin = array(self::CATEGORIE_VIN_GENERIQUE => 'Générique', self::CATEGORIE_VIN_DOMAINE => 'Domaine');
    public static $types_transaction_vins = array(self::TYPE_TRANSACTION_VIN_VRAC, self::TYPE_TRANSACTION_VIN_BOUTEILLE);
    public static $types_transaction_non_vins = array(self::TYPE_TRANSACTION_RAISINS, self::TYPE_TRANSACTION_MOUTS);
    public static $cvo_repartition = array(
        self::CVO_REPARTITION_50_50 => '50/50',
        self::CVO_REPARTITION_100_NEGO => '100% négociant',
        self::CVO_REPARTITION_100_VITI => '100% viticulteur',
        self::CVO_REPARTITION_0_VINAIGRERIE => 'Vinaigrerie');
    public static $statuts_vise = array(self::STATUS_CONTRAT_NONSOLDE, self::STATUS_CONTRAT_SOLDE, self::STATUS_CONTRAT_VISE);
    public static $statuts_labels = array(self::STATUS_CONTRAT_BROUILLON => 'Brouillon',
        self::STATUS_CONTRAT_ATTENTE_SIGNATURE => 'En attente de signature',
        self::STATUS_CONTRAT_VISE => 'En attente de traitement',
        self::STATUS_CONTRAT_VALIDE => 'En attente de traitement',
        self::STATUS_CONTRAT_SOLDE => 'Soldé',
        self::STATUS_CONTRAT_ANNULE => 'Annulé',
        self::STATUS_CONTRAT_NONSOLDE => 'Non Soldé');
    public static $statuts_labels_teledeclaration = array(self::STATUS_CONTRAT_BROUILLON => 'Brouillon',
        self::STATUS_CONTRAT_ATTENTE_SIGNATURE => 'En attente de signature',
        self::STATUS_CONTRAT_VISE => 'En attente de traitement',
        self::STATUS_CONTRAT_VALIDE => 'En attente de traitement',
        self::STATUS_CONTRAT_SOLDE => 'Validé',
        self::STATUS_CONTRAT_ANNULE => 'Annulé',
        self::STATUS_CONTRAT_NONSOLDE => 'Validé');
    public static $statuts_teledeclaration_sorted = array(self::STATUS_CONTRAT_VISE,
        self::STATUS_CONTRAT_VALIDE,
        self::STATUS_CONTRAT_BROUILLON,
        self::STATUS_CONTRAT_ATTENTE_SIGNATURE,
        self::STATUS_CONTRAT_NONSOLDE,
        self::STATUS_CONTRAT_SOLDE);

    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Vrac");
    }

    public function getContenances() {
        $contenances = sfConfig::get('app_vrac_contenances');
        if (!$contenances)
            throw new sfException("Les contenances n'ont pas été renseignée dans le fichier de configuration app.yml");
        return $contenances;
    }

    public function getContenance($k) {
        $contenances = $this->getContenances();

        return $contenances[$k];
    }

    public function getId($id_or_numerocontrat) {
        $id = $id_or_numerocontrat;
        if (strpos($id_or_numerocontrat, 'VRAC-') === false) {
            $id = 'VRAC-' . $id_or_numerocontrat;
        }

        return $id;
    }

    public function getNumeroContrat($id_or_numerocontrat) {

        return str_replace('VRAC-', '', $id_or_numerocontrat);
    }

    public function buildCampagne($date) {

        return ConfigurationClient::getInstance()->buildCampagne($date);
    }

    public function getNextNoContrat() {
        $id = '';
        $date = date('Ymd');
        $contrats = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
	for ($i = 0 ; $i < count($contrats) ; $i++) {
		$contrats[$i] = preg_replace('/1(....)$/', '0$1', $contrats[$i]);
	}
        if (count($contrats) > 0) {
            $id .= ((double) str_replace('VRAC-', '', max($contrats)) + 1);
        } else {
            $id.= $date . '00001';
        }
        return $id;
    }

    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('VRAC-' . $date . '00000')->endkey('VRAC-' . $date . '99999')->execute($hydrate);
    }

    public function findByNumContrat($num_contrat, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->find($this->getId($num_contrat), $hydrate);
    }

    public function retrieveLastDocs($limit = self::RESULTAT_LIMIT) {

        return $this->descending(true)
                        ->startkey(array(0, array()))
                        ->endkey(array(0))
                        ->limit($limit)
                        ->getView('vrac', 'history');
    }


    public function retrieveAllVracsTeledeclares() {

        return $this->descending(true)
                        ->startkey(array(1, array()))
                        ->endkey(array(1))
                        ->getView('vrac', 'history');
    }

    public function retrieveByCampagneEtablissementAndStatut($societe, $campagne, $etablissement = 'tous', $statut = 'tous') {

        if (!preg_match('/[0-9]{4}-[0-9]{4}/', $campagne)) {
            throw new sfException("wrong campagne format ($campagne)");
        }

        $allEtablissementsIds = array_keys($societe->getEtablissementsObj());
        if (!in_array("ETABLISSEMENT-" . $etablissement, $allEtablissementsIds) && $etablissement != 'tous') {
            throw new sfException("wrong etb id ($etablissement)");
        }

        $allStatuts = self::$statuts_teledeclaration_sorted;

        array_unshift($allStatuts,"SOLDENONSOLDE");
        array_unshift($allStatuts,self::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES);
        array_unshift($allStatuts,self::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI);

        if (($key = array_search(self::STATUS_CONTRAT_ATTENTE_SIGNATURE, $allStatuts)) !== false) {
            unset($allStatuts[$key]);
        }

        if (!in_array(strtoupper($statut), $allStatuts) && $statut != 'tous') {
            throw new sfException("wrong statut id ($statut)");
        }

        $etablissements = array();
        if ($etablissement == 'tous') {
            foreach ($allEtablissementsIds as $etablissementsId) {
                $etablissements[] = str_replace("ETABLISSEMENT-", '', $etablissementsId);
            }
        } else {
            $etablissements[] = $etablissement;
        }

        $statuts = array();
        if ($statut == 'tous') {
            $statuts = $allStatuts;
        } elseif ($statut == "SOLDENONSOLDE") {
            $statuts[] = VracClient::STATUS_CONTRAT_NONSOLDE;
            $statuts[] = VracClient::STATUS_CONTRAT_SOLDE;
        } else {
            $statuts[] = $statut;
        }

        return $this->retrieveByCampagneEtablissementsAndStatuts($societe, $campagne, $etablissements, $statuts);
    }

    public function retrieveBySocieteWithInfosLimit($societe, $etbId, $limit = self::RESULTAT_LIMIT) {

        $result = new stdClass();
        $result->contrats = array();
        $this->buildInfosObj($result);
        $campagnes = array();

        $campagnes['current'] = ConfigurationClient::getInstance()->getCurrentCampagne();
        $campagnes['previous'] = ConfigurationClient::getInstance()->getPreviousCampagne($campagnes['current']);

        $statuts = self::$statuts_teledeclaration_sorted;

        $cpt = 0;
        foreach ($statuts as $statut) {
            foreach ($campagnes as $campagne) {
                if ($cpt > $limit) {
                    break;
                }
                $local_result = $this->retrieveByCampagneSocieteAndStatut($campagne, $societe, $statut, $limit);
                if ($statut != VracClient::STATUS_CONTRAT_BROUILLON) {

                    $result->contrats = array_merge($result->contrats, $local_result);
                    $cpt+= count($local_result);
                } else {
                    foreach ($local_result as $brouillon_contrat) {
                        if ($societe->identifiant == substr($brouillon_contrat->value[self::VRAC_VIEW_CREATEURIDENTIFANT], 0, 6)) {
                            $result->contrats[] = $brouillon_contrat;
                            $cpt++;
                        }
                    }
                }
            }
            if ($cpt > $limit) {
                break;
            }
        }


        $brouillon_contrats_current = $this->retrieveByCampagneSocieteAndStatut($campagnes['current'], $societe, VracClient::STATUS_CONTRAT_BROUILLON);
        $brouillon_contrats_previous = $this->retrieveByCampagneSocieteAndStatut($campagnes['previous'], $societe, VracClient::STATUS_CONTRAT_BROUILLON);

        $nb_my_brouillons_current = $this->countBrouillons($societe, $brouillon_contrats_current);
        $nb_my_brouillons_previous = $this->countBrouillons($societe, $brouillon_contrats_previous);
        $result->infos->brouillon = $nb_my_brouillons_current + $nb_my_brouillons_previous;

        $en_attente_contrats_current = $this->retrieveByCampagneSocieteAndStatut($campagnes['current'], $societe, VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE);
        $en_attente_contrats_previous = $this->retrieveByCampagneSocieteAndStatut($campagnes['previous'], $societe, VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE);

        foreach ($en_attente_contrats_current as $contrats_current_obj) {
            $signature_vendeur = (isset($contrats_current_obj->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR])) ?
                    $contrats_current_obj->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR] : null;
            $signature_acheteur = (isset($contrats_current_obj->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR])) ?
                    $contrats_current_obj->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR] : null;
            $signature_courtier = (isset($contrats_current_obj->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER])) ?
                    $contrats_current_obj->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER] : null;
            $tobeSignedByMe = $this->toBeSignedBySociete(VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE, $societe, $signature_vendeur, $signature_acheteur, $signature_courtier);
            $result->infos->a_signer += (int) $tobeSignedByMe;
            $result->infos->en_attente += (int) !$tobeSignedByMe;
        }

        foreach ($en_attente_contrats_previous as $contrats_previous_obj) {
            $signature_vendeur = $contrats_previous_obj->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR];
            $signature_acheteur = $contrats_previous_obj->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR];
            $signature_courtier = $contrats_previous_obj->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER];
            $tobeSignedByMe = $this->toBeSignedBySociete(VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE, $societe, $signature_vendeur, $signature_acheteur, $signature_courtier);
            $result->infos->a_signer += (int) $tobeSignedByMe;
            $result->infos->en_attente += (int) !$tobeSignedByMe;
        }

        return $result;
    }

    private function countBrouillons($societe, $viewResult) {
        $nb_brouillon = 0;
        foreach ($viewResult as $brouillon_contrat) {
            if ($societe->identifiant == substr($brouillon_contrat->value[self::VRAC_VIEW_CREATEURIDENTIFANT], 0, 6)) {
                $nb_brouillon++;
            }
        }
        return $nb_brouillon;
    }

    public function retrieveByCampagneEtablissementsAndStatuts($societe, $campagne, $etablissements, $statuts, $limit = self::RESULTAT_LIMIT) {
        $result = array();
        foreach ($statuts as $statut) {
            if (($statut == VracClient::STATUS_CONTRAT_VISE) || ($statut == VracClient::STATUS_CONTRAT_VALIDE)) {
                continue;
            }
            $byEtbs = array();
            foreach ($etablissements as $etablissement) {

                if ($statut == self::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI) {
                    $local_result = $this->retrieveByCampagneSoussigneAndStatut($campagne, $etablissement, self::STATUS_CONTRAT_ATTENTE_SIGNATURE, $limit);
                    foreach ($local_result as $attente_signature_contrat) {
                        $toBeSigned = $this->toBeSignedBySociete($attente_signature_contrat->value[self::VRAC_VIEW_STATUT], $societe, $attente_signature_contrat->value[self::VRAC_VIEW_SIGNATUREVENDEUR], $attente_signature_contrat->value[self::VRAC_VIEW_SIGNATUREACHETEUR], $attente_signature_contrat->value[self::VRAC_VIEW_SIGNATURECOURTIER]);
                        if ($toBeSigned) {
                            $byEtbs[] = $attente_signature_contrat;
                        }
                    }
                } elseif ($statut == self::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES) {
                    $local_result = $this->retrieveByCampagneSoussigneAndStatut($campagne, $etablissement, self::STATUS_CONTRAT_ATTENTE_SIGNATURE, $limit);
                    foreach ($local_result as $attente_signature_contrat) {
                        $toBeSigned = $this->toBeSignedBySociete($attente_signature_contrat->value[self::VRAC_VIEW_STATUT], $societe, $attente_signature_contrat->value[self::VRAC_VIEW_SIGNATUREVENDEUR], $attente_signature_contrat->value[self::VRAC_VIEW_SIGNATUREACHETEUR], $attente_signature_contrat->value[self::VRAC_VIEW_SIGNATURECOURTIER]);
                        if (!$toBeSigned) {
                            $byEtbs[] = $attente_signature_contrat;
                        }
                    }
                } else {
                    $local_result = $this->retrieveByCampagneSoussigneAndStatut($campagne, $etablissement, $statut, $limit);

                    if ($statut != VracClient::STATUS_CONTRAT_BROUILLON) {
                        $byEtbs = array_merge($byEtbs, $local_result);
                    } else {
                        foreach ($local_result as $brouillon_contrat) {
                            if ($societe->identifiant == substr($brouillon_contrat->value[self::VRAC_VIEW_CREATEURIDENTIFANT], 0, 6)) {
                                $byEtbs[] = $brouillon_contrat;
                            }
                        }
                    }
                }
            }
            $result = array_merge($result, $byEtbs);
        }
        return $result;
    }

    public function retrieveByCampagneSocieteAndStatut($campagne, $societe, $statut, $limit = self::RESULTAT_LIMIT) {

        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");

        $allEtablissements = $societe->getEtablissementsObj();
        $bySoussigne = array();
        foreach ($allEtablissements as $etablissementObj) {
            $etbId = $etablissementObj->etablissement->identifiant;
            $bySoussigneQuery = $this->startkey(array('SOCIETE', $campagne, $etbId, $statut, array()))
                            ->endkey(array('SOCIETE', $campagne, $etbId, $statut))->descending(true);
            if ($limit) {
                $bySoussigneQuery = $bySoussigneQuery->limit($limit);
            }
            $local_result = $bySoussigneQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
            $bySoussigne = array_merge($bySoussigne, $local_result->rows);
        }
        return $bySoussigne;
    }

    public function retrieveByCampagneSoussigneAndStatut($campagne, $soussigneId, $statut, $limit = self::RESULTAT_LIMIT) {

        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");


        $bySoussigneQuery = $this->startkey(array('SOCIETE', $campagne, $soussigneId, $statut, array()))
                        ->endkey(array('SOCIETE', $campagne, $soussigneId, $statut))->descending(true);

        if ($limit) {
            $bySoussigneQuery = $bySoussigneQuery->limit($limit);
        }

        $bySoussigne = $bySoussigneQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
        return $bySoussigne->rows;
    }

    public function listCampagneBySocieteId($societeId) {

        $societe = SocieteClient::getInstance()->findByIdentifiantSociete($societeId);
        $result = array();
        foreach ($societe->getEtablissementsObj() as $etbObj) {
            $result = array_merge($result, $this->listCampagneByEtablissementId($etbObj->etablissement->identifiant));
        }
        return $result;
    }

    private function buildInfosObj(&$result) {
        $result->infos = new stdClass();
        $result->infos->a_signer = 0;
        $result->infos->brouillon = 0;
        $result->infos->en_attente = 0;
    }

    public function retrieveBySoussigne($soussigneId, $campagne, $limit = self::RESULTAT_LIMIT) {
        $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");
        $bySoussigneQuery = $this->startkey(array('STATUT', $soussigneId, $campagne))
                ->endkey(array('STATUT', $soussigneId, $campagne, array()));
        if ($limit) {
            $bySoussigneQuery = $bySoussigneQuery->limit($limit);
        }

        $bySoussigne = $bySoussigneQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
        return $bySoussigne;
    }

    public function retrieveByType($type, $campagne, $limit = self::RESULTAT_LIMIT) {
        $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");
        $bySoussigneTypeQuery = $this->startkey(array('TYPE', $soussigneId, $campagne, $type))
                ->endkey(array('TYPE', $soussigneId, $campagne, $type, array()));

        if ($limit) {
            $bySoussigneTypeQuery = $bySoussigneTypeQuery->limit($limit);
        }
        $bySoussigneType = $bySoussigneTypeQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
        return $bySoussigneType;
    }

    public function retrieveBySoussigneAndStatut($soussigneId, $campagne, $statut, $limit = self::RESULTAT_LIMIT) {
        $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");
        $bySoussigneStatutQuery = $this->startkey(array('STATUT', $soussigneId, $campagne, $statut))
                ->endkey(array('STATUT', $soussigneId, $campagne, $statut, array()));

        if ($limit) {
            $bySoussigneStatutQuery = $bySoussigneStatutQuery->limit($limit);
        }

        $bySoussigneStatut = $bySoussigneStatutQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
        return $bySoussigneStatut;
    }

    public function retrieveBySoussigneAndType($soussigneId, $campagne, $type, $limit = self::RESULTAT_LIMIT) {
        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");
        if (is_array($type)) {
            $typestart = $type['start'];
            $typeend = $type['end'];
        } else {
            $typestart = $type;
            $typeend = $type;
        }
        $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
        $bySoussigneTypeQuery = $this->startkey(array('TYPE', $soussigneId, $campagne, $typestart))
                ->endkey(array('TYPE', $soussigneId, $campagne, $typeend, array()));

        if ($limit) {
            $bySoussigneTypeQuery = $bySoussigneTypeQuery->limit($limit);
        }
        $bySoussigneType = $bySoussigneTypeQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
        return $bySoussigneType;
    }

    public function retrieveBySoussigneStatutAndType($soussigneId, $campagne, $statut, $type, $limit = self::RESULTAT_LIMIT) {
        if (!preg_match('/[0-9]*-[0-9]*/', $campagne))
            throw new sfException("wrong campagne format ($campagne)");
        $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
        $bySoussigneTypeQuery = $this->startkey(array('STATUT', $soussigneId, $campagne, $statut, $type))
                ->endkey(array('STATUT', $soussigneId, $campagne, $statut, $type, array()));

        if ($limit) {
            $bySoussigneTypeQuery = $bySoussigneTypeQuery->limit($limit);
        }
        $bySoussigneType = $bySoussigneTypeQuery->reduce(false)->getView('vrac', 'soussigneidentifiant');
        return $bySoussigneType;
    }

    public function findDocIdByNumArchive($campagne, $num_contrat, $recursive = 0) {

        $doc_id = ArchivageAllView::getInstance()->findDocId("Vrac", $campagne, $num_contrat);

        if ($doc_id) {

            return $doc_id;
        }

        $recursive = $recursive - 1;

        if($recursive < 0) {

            return null;
        }

        return $this->findDocIdByNumArchive(ConfigurationClient::getInstance()->getPreviousCampagne($campagne), $num_contrat, $recursive);
    }

    public function listCampagneByEtablissementId($identifiant) {
        $rows = $this->startkey(array('STATUT', $identifiant))
                        ->endkey(array('STATUT', $identifiant, array()))
                        ->group_level(3)
                        ->getView('vrac', 'soussigneidentifiant')->rows;
        $current = ConfigurationClient::getInstance()->getCurrentCampagne();
        $list = array();
        foreach ($rows as $r) {
            $c = $r->key[2];
            if (!$c) {

                continue;
            }
            $list[$c] = $c;
        }

	$list_complete = array();

	if (count($list)) {
	        krsort($list);
		$first = preg_replace('/-.*/', '', array_pop($list));
		$last = preg_replace('/-.*/', '', array_shift($list));
		if (!$last) $last = $first;
		for($i = $first ; $i <= $last ; $i++) {
			$campagne = $i.'-'.($i + 1);
			$list_complete[$campagne] = $campagne;
		}
	}

        return ConfigurationClient::getInstance()->getCampagneVinicole()->consoliderCampagnesList($list_complete);
    }

    public static function getCsvForEtiquettes($date_debut, $date_fin) {
        if (is_null($date_debut) || is_null($date_fin)) {
            throw new sfException('La date de début et la date de fin sont obligatoires.');
        }
        $date_debut_iso = Date::getIsoDateFromFrenchDate($date_debut);
        $date_fin_iso = Date::getIsoDateFromFrenchDate($date_fin);

        if (str_replace('-', '', $date_fin_iso) < str_replace('-', '', $date_debut_iso)) {
            throw new sfException('La date de fin ne peut etre supérieur à la date de fin.');
        }

        $vracs = VracStatutAndTypeView::getInstance()->findContatsByStatutsAndTypesAndDates(self::$statuts_vise, array_keys(self::$types_transaction), $date_debut_iso, $date_fin_iso . " 99:99:99");

        $result = "\xef\xbb\xbf";
        $result .="RAISON SOCIALE SOCIETE;ADRESSE SOCIETE ;ADRESSE COMPLEMENTAIRE SOCIETE;CODE POSTAL SOCIETE;VILLE SOCIETE\n";
        $adress_tab = array();
        foreach ($vracs as $key => $vrac_row) {
            $vrac = VracClient::getInstance()->find($vrac_row->id, acCouchdbClient::HYDRATE_JSON);

            if (isset($vrac->teledeclare) && $vrac->teledeclare) {
                continue;
            }

            $row_vendeur = self::constructRowForEtiquettes($vrac->vendeur, $vrac->vendeur_identifiant);
            if (!in_array($row_vendeur, $adress_tab)) {
                $result.=$row_vendeur;
                $adress_tab[] = $row_vendeur;
            }

            $row_acheteur = self::constructRowForEtiquettes($vrac->acheteur, $vrac->acheteur_identifiant);
            if (!in_array($row_acheteur, $adress_tab)) {
                $result.=$row_acheteur;
                $adress_tab[] = $row_acheteur;
            }

            $row_mandataire = self::constructRowForEtiquettes($vrac->mandataire, $vrac->mandataire_identifiant);
            if ($row_mandataire != "" && $vrac->mandataire_exist && !in_array($row_mandataire, $adress_tab)) {
                $result.=$row_mandataire;
                $adress_tab[] = $row_mandataire;
            }
        }

        $result = substr($result, 0, strlen($result) - 1);
        return $result;
    }

    protected static function constructRowForEtiquettes($soussigne, $identifiant) {
        if (!$identifiant)
            return "";
        $compte = CompteClient::getInstance()->findByIdentifiant($identifiant);
        if (!$compte)
            return "";
        $societe = $compte->getSociete();
        if (!$societe)
            return "";

        $result = ($societe->exist('raison_sociale')) ? str_replace(";", "", $societe->raison_sociale) . ";" : ";";
        if (!$societe->exist('siege'))
            return $result . ";;;\n";

        $result.= ($societe->siege->exist('adresse')) ? str_replace(";", "", $societe->siege->adresse) . ";" : ";";
        $result.= ($societe->siege->exist('adresse_complementaire')) ? str_replace(";", "", $societe->siege->adresse_complementaire) . ";" : ";";
        $result.= ($societe->siege->exist('code_postal')) ? str_replace(";", "", $societe->siege->code_postal) . ";" : ";";
        $result.= ($societe->siege->exist('commune')) ? str_replace(";", "", $societe->siege->commune) . "\n" : "\n";
        return $result;
    }

    public static function getCsvBySoussigne($vracs) {
        $result = "\xef\xbb\xbf";
        $statuts_libelles = self::getStatuts();
        foreach ($vracs->rows as $value) {
            $cpt = 0;
            $elt = $value->getRawValue()->value;

            foreach ($elt as $key => $champs) {
                $cpt++;
                if ($key == self::VRAC_VIEW_STATUT)
                    $champs = (array_key_exists($champs, $statuts_libelles)) ? $statuts_libelles[$champs] : $champs;
                if ($key == self::VRAC_VIEW_NUMARCHIVE)
                    $champs = "" . $champs;
                if ($key == self::VRAC_VIEW_TYPEPRODUIT)
                    $champs = self::$types_transaction[$champs];
                if ($key == self::VRAC_VIEW_VOLPROP || $key == self::VRAC_VIEW_VOLENLEVE)
                    $champs = sprintf("%01.02f", round($champs, 2));
                $result.='"' . $champs . '"';
                if ($cpt < count($elt))
                    $result.=';';
            }
            $result.="\n";
        }
        return $result;
    }

    public static function getCsvBySociete($vracs) {
        $result = "\xef\xbb\xbf";
        $result.= "numero_contrat;numero_archive;produit_libelle;quantite;prix_unitaire;statut;type_transaction;vendeur_identifiant;vendeur_nom;vendeur_signature;";
        $result.= "acheteur_identifiant;acheteur_nom;acheteur_signature;courtier_identifiant;courtier_nom;courtier_signature\n";

        foreach ($vracs as $contrat) {
            $cpt = 0;

            $quantite = "";
            switch ($contrat->value[self::VRAC_VIEW_TYPEPRODUIT]) {
                case self::TYPE_TRANSACTION_MOUTS:
                case self::TYPE_TRANSACTION_VIN_VRAC:
                    $quantite = $contrat->value[self::VRAC_VIEW_JUS_QUANTITE];
                    break;
                case self::TYPE_TRANSACTION_RAISINS:
                    $quantite = $contrat->value[self::VRAC_VIEW_RAISIN_QUANTITE];
                    break;
                case self::TYPE_TRANSACTION_VIN_BOUTEILLE:
                    $quantite = $contrat->value[self::VRAC_VIEW_BOUTEILLE_QUANTITE];
                    break;
            }

            $result.= $contrat->value[self::VRAC_VIEW_NUMCONTRAT] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_NUMARCHIVE] . ';';

            $result.= $contrat->value[self::VRAC_VIEW_PRODUIT_LIBELLE] . ';';
            $result.= str_replace('.', ',', $quantite) . ';';
            $result.= str_replace('.', ',', $contrat->value[self::VRAC_VIEW_PRIX_UNITAIRE]) . ';';

            $result.= $contrat->value[self::VRAC_VIEW_STATUT] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_TYPEPRODUIT] . ';';

            $result.= $contrat->value[self::VRAC_VIEW_VENDEUR_ID] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_VENDEUR_NOM] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_SIGNATUREVENDEUR] . ';';

            $result.= $contrat->value[self::VRAC_VIEW_ACHETEUR_ID] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_ACHETEUR_NOM] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_SIGNATUREACHETEUR] . ';';

            $result.= $contrat->value[self::VRAC_VIEW_MANDATAIRE_ID] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_MANDATAIRE_NOM] . ';';
            $result.= $contrat->value[self::VRAC_VIEW_SIGNATURECOURTIER] . ';';




            $result.="\n";
        }
        return $result;
    }

    public function retrieveSimilaryContracts($vrac) {
        if (isset($vrac->vendeur_identifiant) || isset($vrac->acheteur_identifiant)) {
            return false;
        }
        $args = array();
        if ($vrac->mandataire_exist) {
            $args = array($vrac->vendeur_identifiant, $vrac->acheteur_identifiant, $vrac->mandataire_identifiant, $vrac->type_transaction);
        } else {
            $args = array($vrac->vendeur_identifiant, $vrac->acheteur_identifiant, '', $vrac->type_transaction);
        }
        if ($vrac->produit) {
            $args[] = $vrac->produit;

            if ($vrac->volume_propose) {
                $args[] = $vrac->volume_propose;
            }
        }
        $start = $this->startkey($args);
        $args[] = array();
        $view = $start->endkey($args)->limit(10)->getView('vrac', 'vracSimilaire');
        if ($vrac->_id)
            $this->filterSimilaryContracts($view, $vrac->_id);
        return $view->rows;
    }

    public function retrieveSimilaryContractsWithProdTypeVol($params) {
        if ((empty($params['vendeur'])) || (empty($params['acheteur'])) || (empty($params['type']))) {

            return false;
        }

        if (empty($params['produit']) && !empty($params['volume'])) {

            return false;
        }

        if (empty($params['volume']) && empty($params['produit'])) {

            return $this->startkey(array($params['vendeur'], $params['acheteur'], $params['mandataire'], $params['type']))
                            ->endkey(array($params['vendeur'], $params['acheteur'], $params['mandataire'], $params['type'], array()))->limit(10)->getView('vrac', 'vracSimilaire');
        }

        if (empty($params['volume'])) {

            return $this->startkey(array($params['vendeur'], $params['acheteur'], $params['mandataire'], $params['type'], $params['produit']))
                            ->endkey(array($params['vendeur'], $params['acheteur'], $params['mandataire'], $params['type'], $params['produit'], array()))->limit(10)->getView('vrac', 'vracSimilaire');
        }

        $volumeBas = ((float) $params['volume']) * 0.95;
        $volumeHaut = ((float) $params['volume']) * 1.05;

        return $this->startkey(array($params['vendeur'], $params['acheteur'], $params['mandataire'], $params['type'], $params['produit'], $volumeBas))
                        ->endkey(array($params['vendeur'], $params['acheteur'], $params['mandataire'], $params['type'], $params['produit'], $volumeHaut, array()))->limit(10)->getView('vrac', 'vracSimilaire');
    }

    public function filterSimilaryContracts($similaryContracts, $vracid) {

        foreach ($similaryContracts->rows as $key => $value) {
            if ($value->id === $vracid) {
                unset($similaryContracts->rows[$key]);
                return;
            }
        }
    }

    public function retrieveByNumeroAndEtablissementAndHashOrCreateIt($id, $etablissement, $hash, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $vrac = $this->retrieveById($id);
        if (!$vrac) {
            $vrac = new Vrac();
            $vrac->vendeur_identifiant = $etablissement;
            $vrac->numero_contrat = $id;
            $vrac->produit = $hash;
        }
        if ($etablissement != $vrac->vendeur_identifiant)
            throw new sfException('le vendeur ne correpond pas à l\'établissement initial');
        if (!preg_match("|^$hash|", $vrac->produit))
            throw new sfException('Le hash du produit ne correpond pas au hash initial (' . $vrac->produit . '<->' . $hash . ')');
        return $vrac;
    }

    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Vrac
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::retrieveDocumentById('VRAC-' . $id, $hydrate);
    }

    public static function getTypes() {
        return array(self::TYPE_TRANSACTION_MOUTS => "Moûts",
            self::TYPE_TRANSACTION_RAISINS => "Raisins",
            self::TYPE_TRANSACTION_VIN_BOUTEILLE => "Conditionné",
            self::TYPE_TRANSACTION_VIN_VRAC => "Vrac");
    }

    public static function getStatuts() {
        return array(self::STATUS_CONTRAT_ANNULE => "Annulé",
            self::STATUS_CONTRAT_NONSOLDE => "Non soldé",
            self::STATUS_CONTRAT_SOLDE => "Soldé");
    }

    public function getLibelleFromId($id, $separation = " ") {
        $id = str_replace('VRAC-', '', $id);
        return sprintf('%s%s%s', substr($id, 0, 8), $separation, substr($id, 8, strlen($id) - 1));
    }

    public function getLibelleContratNum($id) {
        // if(strlen($id)!=13) throw new Exception(sprintf ('Le numéro de contrat %s ne possède pas un bon format.',$id));
        $annee = substr($id, 0, 4);
        $mois = substr($id, 4, 2);
        $jour = substr($id, 6, 2);
        $num = substr($id, 8);
        return $jour . '/' . $mois . '/' . $annee . ' n° ' . $num;
    }

    public function getNumeroArchiveEtDate($id) {
        $c = $this->findByNumContrat($id);
        return $c->numero_archive . ' du ' . $c->date_signature;
    }

    public function retreiveByStatutsTypes($statuts, $types) {
        return VracStatutAndTypeView::getInstance()->findContatsByStatutsAndTypes($statuts, $types);
    }

    public function retreiveByStatutsTypesAndDate($statuts, $types, $date) {
        return VracStatutAndTypeView::getInstance()->findContatsByStatutsAndTypesAndDate($statuts, $types, $date);
    }

    public function retreiveByWaitForOriginal() {
        return VracOriginalPrixDefinitifView::getInstance()->findContatsByWaitForOriginal();
    }

    public function findContatsByWaitForPrixDefinitif($date) {
        return VracOriginalPrixDefinitifView::getInstance()->findContatsByWaitForPrixDefinitif($date);
    }

    public function getMandatants() {

        return array('acheteur' => 'acheteur', 'vendeur' => 'vendeur');
    }

    public function getMaster($id) {
        return $this->find($id);
    }

    public function toBeSignedBySociete($statut, $societe, $signature_vendeur, $signature_acheteur, $signature_courtier) {
        return ($statut === self::STATUS_CONTRAT_ATTENTE_SIGNATURE) &&
                (($societe->isCourtier() && !$signature_courtier) || ($societe->isNegociant() && !$signature_acheteur) || ($societe->isViticulteur() && !$signature_vendeur));
    }

    public function buildEnlevements($vrac) {

        $enlevements = array();
        $mvts_drm = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissement($vrac->vendeur_identifiant);
        $mvts_sv12 = SV12MouvementsConsultationView::getInstance()->getMouvementsByEtablissement($vrac->acheteur_identifiant);
        foreach ($mvts_drm as $key => $mvt) {
            $pos = strpos($mvt->produit_hash, $vrac->produit);
            if($mvt->type_hash == "vrac_details" && ($pos !== false) && $mvt->detail_identifiant == $vrac->_id){
                $enlevements[$mvt->doc_id] = new stdClass();
                $enlevements[$mvt->doc_id]->doc_id = $mvt->doc_id;
                $enlevements[$mvt->doc_id]->type = $mvt->type;
                $enlevements[$mvt->doc_id]->periode = preg_replace('/DRM-([0-9]+)-/','',$mvt->doc_id);
                $enlevements[$mvt->doc_id]->volume = $mvt->volume * -1;
            }
        }
        foreach ($mvts_sv12 as $key => $mvt) {
            $pos = strpos($mvt->produit_hash, $vrac->produit);
            if($pos !== false && $mvt->detail_identifiant == $vrac->_id){
                $enlevements[$mvt->doc_id] = new stdClass();
                $enlevements[$mvt->doc_id]->type = $mvt->type;
                $enlevements[$mvt->doc_id]->doc_id = $mvt->doc_id;
                $enlevements[$mvt->doc_id]->periode = preg_replace('/(-M[0-9]+)/','',preg_replace('/SV12-([0-9]+)-/','',$mvt->doc_id));
                $enlevements[$mvt->doc_id]->volume = $mvt->volume * -1;
            }
        }

        return $enlevements;
    }


}
