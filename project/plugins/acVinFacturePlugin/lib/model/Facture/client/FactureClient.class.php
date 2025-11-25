<?php

class FactureClient extends acCouchdbClient {

    const FACTURE_LIGNE_ORIGINE_TYPE_DRM = "DRM";
    const FACTURE_LIGNE_ORIGINE_TYPE_SV12 = "SV12";
    const FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO = "SV12NEGO";
    const FACTURE_LIGNE_ORIGINE_TYPE_MOUVEMENTSFACTURE = "MouvementsFacture";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE = "propriete";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_NEGOCIANT_RECOLTE = "negociant_recolte";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_NEGOCIANT_RECOLTE_REGULATION = "negociant_recolte_regulation";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT = "contrat";
    const FACTURE_LIGNE_PRODUIT_TYPE_VINS = "contrat_vins";
    const FACTURE_LIGNE_PRODUIT_TYPE_VINS_EXTERNE = "contrat_vins_externe";
    const FACTURE_LIGNE_PRODUIT_TYPE_MOUTS = "contrat_mouts";
    const FACTURE_LIGNE_PRODUIT_TYPE_RAISINS = "contrat_raisins";
    const FACTURE_LIGNE_PRODUIT_TYPE_ECART = "ecart";
    const STATUT_REDRESSEE = 'REDRESSE';
    const STATUT_NONREDRESSABLE = 'NON_REDRESSABLE';
    const TYPE_FACTURE_MOUVEMENT_DRM = "MOUVEMENTS_DRM";
    const TYPE_FACTURE_MOUVEMENT_SV12 = "MOUVEMENTS_SV12";
    const TYPE_FACTURE_MOUVEMENT_SV12_NEGO = "MOUVEMENTS_SV12_NEGO";
    const TYPE_FACTURE_MOUVEMENT_DIVERS = "MOUVEMENTS_DIVERS";

    const FACTURE_PAIEMENT_CHEQUE = "CHEQUE";
    const FACTURE_PAIEMENT_VIREMENT = "VIREMENT";
    const FACTURE_PAIEMENT_ESPECE = "ESPECE";
    const FACTURE_PAIEMENT_CB = "CB";
    const FACTURE_PAIEMENT_AVOIR = "AVOIR";
    const FACTURE_PAIEMENT_PRELEVEMENT_AUTO = "PRELEVEMENT_AUTO";

    public static $codesRemises = array(
        self::FACTURE_PAIEMENT_CHEQUE => '01',
        self::FACTURE_PAIEMENT_VIREMENT => '02',
        self::FACTURE_PAIEMENT_CB => '02',
        self::FACTURE_PAIEMENT_PRELEVEMENT_AUTO => '02',
        self::FACTURE_PAIEMENT_ESPECE => '03',
    );

    public static $origines = array(self::FACTURE_LIGNE_ORIGINE_TYPE_DRM, self::FACTURE_LIGNE_ORIGINE_TYPE_SV12, self::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO, self::FACTURE_LIGNE_ORIGINE_TYPE_MOUVEMENTSFACTURE);
    public static $type_facture_mouvement = array(self::TYPE_FACTURE_MOUVEMENT_DRM => 'Facturation DRM',self::FACTURE_LIGNE_ORIGINE_TYPE_SV12 => 'Facturation SV12 globale',self::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO => 'Facturation SV12 Négociants', self::TYPE_FACTURE_MOUVEMENT_DIVERS => 'Facturation libre');

    public static $types_paiements = array(self::FACTURE_PAIEMENT_CHEQUE => "Chèque", self::FACTURE_PAIEMENT_ESPECE => "Espèce", self::FACTURE_PAIEMENT_VIREMENT => "Virement", self::FACTURE_PAIEMENT_CB=> "Carte Bancaire", self::FACTURE_PAIEMENT_AVOIR => "Avoir");

    public static function getInstance() {
        return acCouchdbManager::getClient("Facture");
    }

    public function getId($identifiant, $numeroFacture) {
        return 'FACTURE-' . $identifiant . '-' . $numeroFacture;
    }

    public function find($id, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {
        $doc = parent::find($id, $hydrate, $force_return_ls);

        if(!$doc && $compte = CompteClient::getInstance()->findByLogin(explode("-", $id)[1])) {

            return parent::find(str_replace(explode("-", $id)[1], $compte->getSociete()->identifiant, $id), $hydrate, $force_return_ls);
        }

        return $doc;
    }

    public function getNextNoFacture($idClient, $date) {
        $id = '';
        $facture = self::getAtDate($idClient, $date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($facture) > 0) {
            $id .= ((double) str_replace('FACTURE-' . $idClient . '-', '', max($facture)) + 1);
        } else {
            $id.= $date . '01';
        }
        return $id;
    }

    public function getAtDate($idClient, $date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('FACTURE-' . $idClient . '-' . $date . '00')->endkey('FACTURE-' . $idClient . '-' . $date . '99')->execute($hydrate);
    }

    public function createDocFromMouvements($mouvementsSoc, $societe, $modele, $date_facturation, $message_communication = null, $interpro = null) {
        $facture = new Facture();
        if ($interpro) {
            $facture->add('interpro', $interpro);
        }
        $biggestMouvementSocDate = null;
        if ($modele == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO) {
            if (!$societe->isNegociant()) {
              return null;
            }
            $new_mvt = [];
            foreach ($mouvementsSoc as $mouvementSoc) {
                if ($mouvementSoc->origine != FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12) {
                    continue;
                }
                foreach ($mouvementSoc->origines as $o) {
                    if (strpos($o, $societe->identifiant) !== false) {
                        $new_mvt[] = $mouvementSoc;
                        continue;
                    }
                }
            }
            $mouvementsSoc = $new_mvt;
        }
        foreach ($mouvementsSoc as $mouvementSoc) {
          if (isset($mouvementSoc->date) && $mouvementSoc->date > $biggestMouvementSocDate) {
            $biggestMouvementSocDate = $mouvementSoc->date;
          }
        }
        $facture->checkModeCalculTotalTaxe();
        $facture->storeDatesCampagne($date_facturation, $biggestMouvementSocDate);
        $facture->constructIds($societe);
        $facture->storeDeclarant($societe);

        $famille = ($societe->type_societe != SocieteClient::TYPE_OPERATEUR)? SocieteClient::TYPE_AUTRE : $societe->famille;

        $facture->storeLignesFromMouvements($mouvementsSoc, $famille, $modele);
        $facture->updateTotalHT();
        $facture->updateAvoir();
        $facture->updateTotaux();
        $facture->storeOrigines();
        if ($modele == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_DRM) {
            $facture->arguments->add(FactureClient::TYPE_FACTURE_MOUVEMENT_DRM, FactureClient::TYPE_FACTURE_MOUVEMENT_DRM);
        } elseif ($modele == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12) {
              $facture->arguments->add(FactureClient::TYPE_FACTURE_MOUVEMENT_SV12, FactureClient::TYPE_FACTURE_MOUVEMENT_SV12);
        } elseif ($modele == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO) {
              $facture->arguments->add(FactureClient::TYPE_FACTURE_MOUVEMENT_SV12_NEGO, FactureClient::TYPE_FACTURE_MOUVEMENT_SV12_NEGO);
        } elseif ($modele == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_MOUVEMENTSFACTURE) {
            $facture->arguments->add(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS, FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS);
        }
        if (trim($message_communication)) {
            $facture->addOneMessageCommunication($message_communication);
        }
        $facture->storeEmetteur();
        if ($facture->total_ht == 0 && count($facture->lignes) == 0) {
            return null;
        }
        return $facture;
    }

    private $documents_origine = array();

    public function getDocumentOrigine($id) {
        if (!array_key_exists($id, $this->documents_origine)) {
            $this->documents_origine[$id] = acCouchdbManager::getClient()->find($id);
        }
        return $this->documents_origine[$id];
    }

    public function findByIdentifiant($identifiant) {
        return $this->find('FACTURE-' . $identifiant);
    }

    public function findBySocieteAndId($idSociete, $idFacture) {
        return $this->find('FACTURE-' . $idSociete . '-' . $idFacture);
    }

    public function getReduceLevelForFacturation($no_reduce = false) {
        if ($no_reduce) {
            return null;
        }
        return MouvementfactureFacturationView::KEYS_VRAC_DEST + 1;
    }

    public function getMouvementsForMasse($interpro, $regions, $no_reduce = false) {
        if (!$regions) {
            return MouvementfactureFacturationView::getInstance()->getMouvements(0, 1, $interpro, $this->getReduceLevelForFacturation($no_reduce));
        }
        $mouvementsByRegions = array();
        foreach ($regions as $region) {
            $mouvementsByRegions = array_merge(MouvementfactureFacturationView::getInstance()->getMouvementsFacturablesByRegions(0, 1, $interpro, $region, $this->getReduceLevelForFacturation($no_reduce)), $mouvementsByRegions);
        }

        ksort($mouvementsByRegions);

        return $mouvementsByRegions;
    }

        public function getMouvementsNonFacturesBySoc($mouvements) {
        $generationFactures = array();
        foreach ($mouvements as $key => $mouvement) {
            $societe_id = EtablissementClient::getInstance()->getSocieteIdentifiant($mouvement->etablissement_identifiant);

            if (isset($generationFactures[$societe_id])) {
                $generationFactures[$societe_id][$key] = $mouvement;
            } else {
                $generationFactures[$societe_id] = array();
                $generationFactures[$societe_id][$key] = $mouvement;
            }
        }
        return $generationFactures;
    }

    public function retrieveMouvement($identifiant, $idDoc, $mouvKey) {
        if ($doc = Factureclient::getInstance()->getDocumentOrigine($idDoc)) {
          return $doc->findMouvement($mouvKey, $identifiant);
        }
        return null;
    }

    public function filterWithParameters($mouvementsBySoc, $parameters, $ignore_somme_nulle = true) {
        $date_mouvement = null;
        if (isset($parameters['date_mouvement']) && $parameters['date_mouvement']) {
            $date_mouvement = Date::getIsoDateFromFrenchDate($parameters['date_mouvement']);
        }
        $modele = null;
        if (isset($parameters['modele']) && $parameters['modele']) {
            $modele = $parameters['modele'];
            if ($modele == self::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO) {
                $modele = self::FACTURE_LIGNE_ORIGINE_TYPE_SV12;
            }
        }

        foreach ($mouvementsBySoc as $identifiant => $mouvements) {
            foreach ($mouvements as $key => $mouvement) {
                $farDateMvt = $this->getGreatestDate($mouvement->date);
                if ($date_mouvement && Date::sup($farDateMvt, $date_mouvement)) {
                    unset($mouvements[$key]);
                    $mouvementsBySoc[$identifiant] = $mouvements;
                    continue;
                }
                if ($modele && !in_array($modele, self::$origines)) {
                    unset($mouvements[$key]);
                    $mouvementsBySoc[$identifiant] = $mouvements;
                    continue;
                }
                if ($modele && $modele != $mouvement->origine) {
                    unset($mouvements[$key]);
                    $mouvementsBySoc[$identifiant] = $mouvements;
                    continue;
                }
            }
        }
        if ($ignore_somme_nulle) {
          foreach ($mouvementsBySoc as $identifiant => $mouvements) {
            $somme = 0;
            foreach ($mouvements as $key => $mouvement) {
                $prix = $mouvement->prix_ht;
                if (!$prix) {
                    unset($mouvementsBySoc[$identifiant][$key]);
                    continue;
                }
                $somme += $prix;
            }
            $somme = $this->ttc($somme);

            if (count($mouvementsBySoc[$identifiant]) == 0) {
                $mouvementsBySoc[$identifiant] = null;
            }
            if (isset($parameters['seuil']) && $parameters['seuil']) {
                if (($somme < $parameters['seuil']) && ($somme >= 0)) {
                    $mouvementsBySoc[$identifiant] = null;
                }
            }
          }
        }
        $mouvementsBySoc = $this->cleanMouvementsBySoc($mouvementsBySoc);
        return $mouvementsBySoc;
    }

    private function getGreatestDate($dates) {
        if (is_string($dates))
            return $dates;
        if (is_array($dates)) {
            $dateres = $dates[0];
            foreach ($dates as $date) {
                if (Date::sup($date, $dateres))
                    $dateres = $date;
            }
            return $dateres;
        }
        throw new sfException("La date du mouvement ou le tableau de date est mal formé " . print_r($dates, true));
    }

    private function cleanMouvementsBySoc($mouvementsBySoc) {
        if (count($mouvementsBySoc) == 0)
            return null;
        foreach ($mouvementsBySoc as $identifiant => $mouvement) {
            if (!$mouvement || !count($mouvement)) {
                unset($mouvementsBySoc[$identifiant]);
            }
        }
        return $mouvementsBySoc;
    }

    public function createFacturesBySociete($societe, $parameters) {
        if (!isset($parameters['interpro'])) {
            $parameters['interpro'] = null;
        }
        $mouvements = array($societe->identifiant => MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($societe, $parameters['interpro']));
        foreach($mouvements as $mouvements_societe) {
            foreach ($mouvements_societe as $mvt) {
                $mvt->origines = array($mvt->origines);
            }
        }
        $mouvements = FactureClient::getInstance()->filterWithParameters($mouvements, $parameters);

        if(!count($mouvements)) {

            return null;
        }

        $mouvements = $mouvements[$societe->identifiant];

        if(!$mouvements || !count($mouvements)) {
            return null;
        }
        $facture = $this->createDocFromMouvements($mouvements,
                                            $societe,
                                            $parameters['modele'],
                                            $parameters['date_facturation'],
                                            $parameters['message_communication'],
                                            $parameters['interpro']);

        return $facture;
    }

    public function createGenerationForOneFacture($facture) {
        $generation = new Generation();
        $generation->date_emission = date('YmdHis');
        $generation->type_document = GenerationClient::TYPE_DOCUMENT_FACTURES;
        $generation->documents = array();
        $generation->somme = $facture->total_ttc;
        $generation->add('documents')->add(null, $facture->_id);

        return $generation;
    }

    private function ttc($p) {
        return $p + $p * 0.2;
    }

    public function getTypes() {
        return array(FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_ECART,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS);
    }

    public function getProduitsFromTypeLignes($lignes) {
        $produits = array();
        foreach ($lignes as $ligne) {
            if (array_key_exists($ligne, $produits)) {
                $produits[$ligne][] = $ligne;
            } else {
                $produits[$ligne] = array();
                $produits[$ligne][] = $ligne;
            }
        }
        return $produits;
    }

    public function isRedressee($factureview) {
        return ($factureview->value[FactureSocieteView::VALUE_STATUT] == self::STATUT_REDRESSEE);
    }

    public function isRedressable($factureview) {
        return !$this->isRedressee($factureview) && $factureview->value[FactureSocieteView::VALUE_STATUT] != self::STATUT_NONREDRESSABLE;
    }

    public function getTypeLignePdfLibelle($typeLibelle) {
        if ($typeLibelle == self::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE)
            return 'Sorties de propriété';
        switch ($typeLibelle) {
            case self::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS:
                return 'Sorties de contrats moûts';

            case self::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS:
                return 'Sorties de contrats raisins';

            case self::FACTURE_LIGNE_PRODUIT_TYPE_VINS:
                return 'Sorties de contrats vins';

            case self::FACTURE_LIGNE_PRODUIT_TYPE_ECART:
                return 'Sorties raisins et moûts';
        }
        return '';
    }

    public function defactureCreateAvoirAndSaveThem(Facture $f) {
        if (!$f->isRedressable()) {
            return;
        }
        $avoir = clone $f;
        $soc = SocieteClient::getInstance()->find($avoir->identifiant);
        $avoir->constructIds($soc, $f->region);
        $f->add('avoir', $avoir->_id);
        $f->save();
        foreach ($avoir->lignes as $type => $lignes) {
            if($lignes->exist('quantite')) {
                $lignes->quantite *= -1;
            }
            $lignes->montant_ht *= -1;
            $lignes->montant_tva *= -1;
            foreach ($lignes->details as $id => $ligne) {
                $ligne->quantite *= -1;
                $ligne->montant_ht *= -1;
                $ligne->montant_tva *= -1;
            }
        }
        $avoir->total_ttc *= -1;
        $avoir->total_ht *= -1;
        $avoir->total_taxe *= -1;
        $avoir->remove('echeances');
        $avoir->add('echeances');
        $avoir->statut = self::STATUT_NONREDRESSABLE;
        $avoir->storeDatesCampagne(date('Y-m-d'));
        $avoir->numero_archive = null;
        $avoir->numero_piece_comptable_origine = $avoir->numero_piece_comptable;
        $avoir->numero_piece_comptable = null;
        $avoir->versement_comptable = 0;
        $avoir->add('taux_tva', round($f->getTauxTva(), 2));
        $avoir->save();

        $f->defacturer();
        $f->save();
        return $avoir;
    }

    public function findAll() {
        return FactureEtablissementView::getInstance()->getAllFacturesForCompta();
    }

    public function getFacturesByCompte($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $ids = $this->startkey(sprintf("FACTURE-%s-%s", $identifiant, "0000000000"))
                        ->endkey(sprintf("FACTURE-%s-%s", $identifiant, "9999999999"))
                        ->execute(acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();

        $factures = array();

        foreach ($ids as $id) {
            $factures[$id] = FactureClient::getInstance()->find($id, $hydrate);
        }

        krsort($factures);

        return $factures;
    }

    public function getDateCreation($id) {
        $d = substr($id, -10, 8);
        $matches = array();
        if (preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', $d, $matches)) {
            return $matches[3] . '/' . $matches[2] . '/' . $matches[1];
        }
        return '';
    }

    public function getTypeFactureMouvement() {
        $type_mouvement = self::$type_facture_mouvement;

        if(!SV12Configuration::getInstance()->isActif()) {
            unset($type_mouvement[FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12]);
            unset($type_mouvement[FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO]);
        }

        return $type_mouvement;
    }

    public function getTauxTva($date) {
        $date = str_replace('-', '', $date);
        $taux = 0.0;
        foreach (FactureConfiguration::getInstance()->getTauxTva() as $d => $t) {
            if ($date >= $d) {
                $taux = round($t, 2);
            }
        }
        return $taux;
    }

    public static function generateAuthKey($id)
    {
        if(!sfConfig::get('app_secret')) {

            throw new Exception("Le \"app_secret\" doit être configuré pour pouvoir générer les url authentifiantes");
        }

        return hash('md5', $id . sfConfig::get('app_secret'));
    }
}
