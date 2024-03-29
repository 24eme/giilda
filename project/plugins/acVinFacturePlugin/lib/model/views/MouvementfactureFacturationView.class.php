<?php

class MouvementfactureFacturationView extends acCouchdbView {

    const KEYS_FACTURE = 0;
    const KEYS_FACTURABLE = 1;
    const KEYS_INTERPRO = 2;
    const KEYS_REGION = 3;
    const KEYS_ETB_ID = 4;
    const KEYS_ORIGIN = 5;
    const KEYS_MATIERE = 6;
    const KEYS_PRODUIT_ID = 7;
    const KEYS_PERIODE = 8;
    const KEYS_DATE = 9;
    const KEYS_CONTRAT_ID = 10;
    const KEYS_VRAC_DEST = 11;
    const KEYS_MVT_TYPE = 12;
    const KEYS_DETAIL_ID = 13;
    const KEYS_TYPE_DRM = 14;
    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_QUANTITE = 2;
    const VALUE_PRIX_UNITAIRE = 3;
    const VALUE_VRAC_DEST = 4;
    const VALUE_DETAIL_LIBELLE = 5;
    const VALUE_ID_DOC = 6;
    const VALUE_ID_ORIGINE = 7;

    public static function getInstance() {

        return acCouchdbManager::getView('mouvementfacture', 'facturation');
    }

    protected function getRowsBySociete($societe, $facturee, $facturable, $reduceLevel = false, $interpro = null) {
        try {
            $paramRegion = ($societe->type_societe != SocieteClient::TYPE_OPERATEUR) ? SocieteClient::TYPE_AUTRE : $societe->getRegionViticole();
        } catch (Exception $e) {
            $paramRegion = array();
        }

        $rows = $this->getRowsByIdentifiant(EtablissementClient::getInstance()->getFirstIdentifiant($societe->identifiant), EtablissementClient::getInstance()->getLastIdentifiant($societe->identifiant), $paramRegion, $facturee, $facturable, $reduceLevel, $interpro);

        $identifiantsSupplementaire = array();
        foreach($societe->etablissements as $id => $infos) {
            if(EtablissementClient::getInstance()->getFirstIdentifiant($societe->identifiant) == str_replace("ETABLISSEMENT-", "", $id)) {
                continue;
            }
            if(EtablissementClient::getInstance()->getFirstIdentifiant($societe->identifiant) != $societe->identifiant && strpos($id, $societe->identifiant) !== false) {
                continue;
            }
            $identifiantsSupplementaire[str_replace("ETABLISSEMENT-", "", $id)] = str_replace("ETABLISSEMENT-", "", $id);
        }
        foreach($identifiantsSupplementaire as $identifiant) {
            $rows = array_merge($rows, $this->getRowsByIdentifiant($identifiant, $identifiant, $paramRegion, $facturee, $facturable, $reduceLevel, $interpro));
        }

        return $rows;
    }

    protected function getRowsByIdentifiant($identifiantStart, $identifiantEnd, $paramRegion, $facturee, $facturable, $reduceLevel = false, $interpro = null) {
        $view = $this->client
                        ->startkey(array($facturee, $facturable, $interpro, $paramRegion, $identifiantStart))
                        ->endkey(array($facturee, $facturable, $interpro, $paramRegion, $identifiantEnd, array()));
        if(!$reduceLevel) {
            $view->reduce(false);
        } else {
            $view->reduce(true)->group_level($reduceLevel);
        }

        return $view->getView($this->design, $this->view)->rows;
    }

    protected function getMouvementsBySociete($societe, $facturee, $facturable, $interpro = null) {

        return $this->getRowsBySociete($societe, $facturee, $facturable, false, $interpro);
    }

    public function getMouvementsBySocieteWithReduce($societe, $facturee, $facturable, $level, $interpro = null) {

        if($societe == SocieteClient::TYPE_OPERATEUR) {
            // On s'assure qu'il y a une region viticole pour les opérateurs car cette méthode renvoi une Exception si ce n'est pas le cas
            $societe->getRegionViticole();
        }

        return $this->buildMouvements($this->consolidationMouvements($this->getRowsBySociete($societe, $facturee, $facturable, $level, $interpro)));
    }

    protected function consolidationMouvements($rows) {
        foreach ($rows as $row) {
            $rows_mouvements = $this->client
                            ->startkey($row->key)
                            ->endkey(array_merge($row->key, array(array())))
                            ->reduce(false)
                        ->getView($this->design, $this->view)->rows;

            $row->value[self::VALUE_ID_ORIGINE] = array();
            foreach ($rows_mouvements as $row_mouvement) {
                $lastIndex = count($row_mouvement->value) - 1;
                $row->value[self::VALUE_ID_ORIGINE] = array_merge($row->value[self::VALUE_ID_ORIGINE], array($row_mouvement->value[$lastIndex]));
            }
        }

        return $rows;
    }

    public function getMouvementsNonFacturesBySociete($societe, $interpro = null) {

        return $this->buildMouvements($this->getMouvementsBySociete($societe, 0, 1, $interpro));
    }

    public function getMouvementsAll($facturee) {

        return $this->buildMouvements($this->client
                                ->startkey(array($facturee))
                                ->reduce(false)
                                ->endkey(array($facturee, array()))
                                ->getView($this->design, $this->view)->rows);
    }

    private function queryMouvements($facturee, $facturable, $interpro = null, $region = null, $level = null) {
        $start = [$facturee,$facturable, $interpro];
        $end = [$facturee,$facturable, $interpro];
        if ($region) {
            $start[] = $region;
            $end[] = $region;
        }
        $end[] = [];
        $view = $this->client->startkey($start)->endkey($end);
        if ($level) {
            $view->reduce(true)->group_level($level);
        } else {
            $view->reduce(false);
        }
        return $view->getView($this->design, $this->view)->rows;
    }

    public function getMouvementsEnAttente($interpro = null, $region = null) {
        return $this->queryMouvements(0, 1, $interpro, $region);
    }

    public function getMouvements($facturee, $facturable, $interpro, $level) {
        return $this->buildMouvements(
                    $this->consolidationMouvements(
                        $this->queryMouvements($facturee, $facturable, $interpro, null, $level)
                ));
    }

    public function getMouvementsFacturablesByRegions($facturee, $facturable, $interpro, $region, $level) {
        return $this->buildMouvements(
                    $this->consolidationMouvements(
                        $this->queryMouvements($facturee, $facturable, $interpro, $region, $level)
                ));
    }

    public function buildMouvements($rows) {
        $mouvements = array();
        $i = 0;
        foreach ($rows as $row) {
            $mouvement = $this->buildMouvement($row);
            $mouvements[str_replace("-", "", $mouvement->date).sprintf("%05d",$i).md5(serialize($mouvement))] = $mouvement;
            $i++;
        }

        ksort($mouvements);

        return $mouvements;
    }

    protected function buildMouvement($row) {
        $mouvement = new stdClass();
        $mouvement->date = $row->key[self::KEYS_DATE];
        $mouvement->etablissement_identifiant = $row->key[self::KEYS_ETB_ID];
        $mouvement->produit_hash = $row->key[self::KEYS_PRODUIT_ID];
        $mouvement->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
        $mouvement->vrac_destinataire = $row->key[self::KEYS_VRAC_DEST];
        $mouvement->type_libelle = $row->value[self::VALUE_TYPE_LIBELLE];
        $mouvement->origine = $row->key[self::KEYS_ORIGIN];
        $mouvement->matiere = $row->key[self::KEYS_MATIERE];
        $mouvement->detail_libelle = $row->value[self::VALUE_DETAIL_LIBELLE];
        $mouvement->quantite = round($row->value[self::VALUE_QUANTITE], FloatHelper::getInstance()->getMaxDecimalAuthorized());
        $mouvement->prix_unitaire = $row->value[self::VALUE_PRIX_UNITAIRE];
        $mouvement->prix_ht = $mouvement->quantite * $row->value[self::VALUE_PRIX_UNITAIRE];
        $mouvement->id_doc = $row->value[self::VALUE_ID_DOC];
        $mouvement->vrac_numero = $row->key[self::KEYS_CONTRAT_ID];
        $mouvement->origines = $row->value[self::VALUE_ID_ORIGINE];
        $mouvement->facturable = $row->key[self::KEYS_FACTURABLE];
        $mouvement->region = $row->key[self::KEYS_REGION];
        $mouvement->interpro = $row->key[self::KEYS_INTERPRO];
        if ($mouvement->origine == "MouvementsFacture") {
            $mouvement->nom_facture = $mouvement->matiere;
        }
        if(array_key_exists(self::KEYS_MVT_TYPE, $row->key)) {
            $mouvement->type_hash = $row->key[self::KEYS_MVT_TYPE];
        }
        if(array_key_exists(self::KEYS_TYPE_DRM, $row->key)) {
            $mouvement->type_drm = $row->key[self::KEYS_TYPE_DRM];
        }
        if(array_key_exists(self::KEYS_DETAIL_ID, $row->key)) {
            $mouvement->detail_identifiant = $row->key[self::KEYS_DETAIL_ID];
        }

        return $mouvement;
    }

    public function createOrigine($famille, $mouvement) {
        $origine_libelle = null;
        if(preg_match('/^'.FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_NEGOCIANT_RECOLTE.'/', $mouvement->matiere) && $mouvement->quantite < 0) {
            $origine_libelle = "Régularisation";
        }

        if(preg_match('/^'.FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_NEGOCIANT_RECOLTE.'/', $mouvement->matiere) && $mouvement->quantite > 0) {
            $origine_libelle = "Récolte";
            if($mouvement->detail_libelle) {
                $origine_libelle .= " (".$mouvement->detail_libelle.")";
            }
        }

        if(!$mouvement->vrac_destinataire) {

            return $origine_libelle;
        }

        if($origine_libelle) {
            $origine_libelle .= " ";
        }
        
        $isProduitFirst = FactureConfiguration::getInstance()->isPdfProduitFirst();

        if (($mouvement->origine == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_DRM)
          || ($mouvement->origine == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12)) {

            $type_contrat = "";
            if($mouvement->origine == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12){
              $type_contrat =  'Achat ';
            }else{
              $type_contrat = 'Contrat ';
            }

            if ($famille == SocieteClient::TYPE_OPERATEUR) {
                if (FactureConfiguration::getInstance()->getIdContrat() == 'ID' ) {
                    $idContrat = intval(substr($mouvement->vrac_numero, -6));
                }else{
                    $idContrat = $mouvement->detail_libelle;
                }
                $origine_libelle .= 'Contrat n° ' . $idContrat;
            }
            $origine_libelle .= ' (' . $mouvement->vrac_destinataire . ') ';

            if($isProduitFirst){
              $origine_libelle = $type_contrat . $mouvement->vrac_destinataire;
            }
            return $origine_libelle;
        }
    }

}
