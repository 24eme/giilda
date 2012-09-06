<?php

class FactureClient extends acCouchdbClient {

    const FACTURE_LIGNE_ORIGINE_TYPE_DRM = "DRM";
    const FACTURE_LIGNE_ORIGINE_TYPE_SV = "SV";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE = "Propriete";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT = "Contrat";
    const FACTURE_LIGNE_PRODUIT_TYPE_VINS = "Vins";
    const FACTURE_LIGNE_PRODUIT_TYPE_MOUTS = "Mouts";
    const FACTURE_LIGNE_PRODUIT_TYPE_RAISINS = "Raisins";
    
    
    const MAX_LIGNE_TEMPLATE_ONEPAGE = 30;
    const MAX_LIGNE_TEMPLATE_TWOPAGE = 70;
    
    const TEMPLATE_ONEPAGE = 1;
    const TEMPLATE_TWOPAGE = 2;
    const TEMPLATE_MOREPAGE = 3;

    public static function getInstance() {
        return acCouchdbManager::getClient("Facture");
    }

    public function getId($client_reference, $identifiant) {
        return 'FACTURE-' . $client_reference . '-' . $identifiant;
    }

    public function createDoc($factures, $etablissement, $date_facturation = null) {
        $facture = new Facture();
        $numPage = '01';
        $facture->identifiant = date('Ymd') . $numPage;
        $facture->date_emission = date('Y-m-d');        
        $facture->date_facturation = $date_facturation;
        if(!$facture->date_facturation) $facture->date_facturation=date('Y-m-d');
        $facture->campagne = '2011-2012';
        $facture->emetteur->adresse = 'Chateau de la Frémoire';
        $facture->emetteur->code_postal = '44120';
        $facture->emetteur->ville = 'VERTOU';
        $facture->emetteur->service_facturation = 'Nelly ALBERT';
        $facture->emetteur->telephone = '02.47.60.55.12';
        $facture->client_identifiant = $etablissement->_id;
        $facture->client_reference = $etablissement->identifiant;
        $facture->client->raison_sociale = $etablissement->raison_sociale;
        $facture->client->adresse = $etablissement->siege->adresse;
        $facture->client->code_postal = $etablissement->siege->code_postal;
        $facture->client->ville = $etablissement->siege->commune;
        $facture->_id = $this->getId($etablissement->identifiant, $facture->identifiant);
        $facture->origines = array();

        $cptLigne = 0;
        $origines = array();
        foreach ($factures as $f) {
            $current_ligne = $this->createFactureLigne($f, $facture);
            $facture->add("lignes")->add($cptLigne, $current_ligne);
            $origines[$f->value[FactureClient::MOUVEMENTS_VALUES_ID]] = $f->value[FactureClient::MOUVEMENTS_VALUES_ID];
            $cptLigne++;
        }
        $facture->origines = $origines;


        $this->createFacturePapillons($facture);

        $facture->total_ttc = $this->ttc($facture->total_ht);
        return $facture;
    }

    private function createFactureLigne($f, $facture) {
        $f->value[FactureMouvementsDRMView::VALUE_VOLUME] = -1 * $f->value[FactureMouvementsDRMView::VALUE_VOLUME];

        $cvo = $f->value[FactureMouvementsDRMView::VALUE_VOLUME] * $f->value[FactureMouvementsDRMView::VALUE_CVO];
        $ligne = array('origine_type' => $f->key[FactureMouvementsDRMView::KEYS_ORIGIN],
            'origine_identifiant' => $f->value[FactureMouvementsDRMView::VALUE_NUMERO],
            'origine_date' => $f->key[FactureMouvementsDRMView::KEYS_PERIODE],
            'produit_type' => $f->key[FactureMouvementsDRMView::KEYS_MVT_TYPE],
            'produit_libelle' => $f->value[FactureMouvementsDRMView::VALUE_PRODUIT_LIBELLE],
            'produit_hash' => $f->key[FactureMouvementsDRMView::KEYS_PRODUIT_ID],
            'volume' => $f->value[FactureMouvementsDRMView::VALUE_VOLUME],
            'cotisation_taux' => $f->value[FactureMouvementsDRMView::VALUE_CVO],
            'montant_ht' => $cvo,
            'cle_mouvement' => $f->value[FactureMouvementsDRMView::VALUE_MD5_CLE]);
        $facture->total_ht += $cvo;

        $ligne = $this->createFactureLigneContrat($ligne, $f);

        return $ligne;
    }

    private function createFacturePapillons($facture) {
        foreach ($facture->lignes as $ligne) {
            switch ($ligne['produit_type']) {
                case FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS:
                case FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS:
                    if (strstr($ligne['produit_hash'], 'mentions/SL/')) {
                        $this->createOrUpdateEcheanceC($ligne, $facture);
                    } else {
                        $this->createOrUpdateEcheanceB($ligne, $facture);
                    }
                    break;
                default :
                    $this->createOrUpdateEcheanceA($ligne, $facture);
                    break;
            }
        }
    }

    private function createOrUpdateEcheanceA($l, $facture) {
        $l['echeance_code'] = 'A';
        $date = date('Y-m-d', strtotime("+60 days"));
        $montant_ttc = $this->ttc($l['montant_ht']);
        $this->updateEcheance('A', $date, $montant_ttc, $facture);
    }

    private function createOrUpdateEcheanceB($l, $facture) {
        $l['echeance_code'] = 'B';
        $date = date('Ymd');
        $d1 = date('Y', strtotime("-1 years")) . '0801'; // 01/08/N-1
        $d2 = date('Y') . '0331'; // 31/03/N
        $d3 = date('Y') . '0531'; // 31/05/N    
        //        
//          if(01/08/N-1 < date < 31/03/N) { 50% au 31/03 et 50% au 31/05 }            
        if (($d1 < $date) && ($date < $d2)) {
            $montant_ttc = $this->ttc($l['montant_ht']) * 0.5;
            $dateEcheance1 = date('Y') . '-03-31';
            $this->updateEcheance('B', $dateEcheance1, $montant_ttc, $facture);

            $dateEcheance2 = date('Y') . '-05-31';
            $this->updateEcheance('B', $dateEcheance2, $montant_ttc, $facture);
            return;
        }

//          if(01/04/N < date < 31/05/N)   { 50% comptant et  50% au 31/05 }              
        if (($d2 < $date) && ($date <= $d3)) {
            $montant_ttc = $this->ttc($l['montant_ht']) * 0.5;
            $dateEcheance1 = date('Y-m-d');
            $this->updateEcheance('B', $dateEcheance1, $montant_ttc, $facture);

            $dateEcheance2 = date('Y') . '-05-31';
            $this->updateEcheance('B', $dateEcheance2, $montant_ttc, $facture);
            return;
        }

//            if(date > 31/05/N) { 100% comptant } 
        if ($date > $d3) {
            $this->updateEcheance('B', date('Y-m-d'), $this->ttc($l['montant_ht']), $facture);
            return;
        }
    }

    private function createOrUpdateEcheanceC($l, $facture) {
        $l['echeance_code'] = 'C';
        $date = date('Y') . '-09-30';
        $montant_ttc = $this->ttc($l['montant_ht']);
        $this->updateEcheance('C', $date, $montant_ttc, $facture);
    }

    private function updateEcheance($echeance_code, $date, $montant_ttc, $facture) {
        $Aexist = false;
        foreach ($facture->echeances as $e) {
            if (($e['echeance_code'] == $echeance_code) && ($e['echeance_date'] == $date)) {
                $e['montant_ttc'] += $montant_ttc;
                $Aexist = true;
                break;
            }
        }
        if (!$Aexist) {
            $echeance = array();
            $echeance['echeance_code'] = $echeance_code;
            $echeance['montant_ttc'] = $montant_ttc;
            $echeance['echeance_date'] = $date;
            $facture->add("echeances")->add(count($facture->echeances), $echeance);
        }
    }

    private function createFactureLigneContrat($ligne, $f) {
        $ligne['contrat_identifiant'] = '';
        $ligne['contrat_libelle'] = '';

        // CONTRAT-XXXXXXX-XXXX si mouvement contrat
        //le libellé du contrat tel qu'il apparait sur la facture genre "n° XXXXXX du JJ/MM/AAAA

        switch ($f->key[FactureClient::MOUVEMENTS_KEYS_MVT_TYPE]) {
            case 'sorties/vrac': {
                    $ligne['mouvement_type'] = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT;
                    $ligne['contrat_identifiant'] = $f->value[FactureClient::MOUVEMENTS_VALUES_TYPE_TRANS]; //Contrat id
                    $ligne['contrat_libelle'] = $f->value[FactureClient::MOUVEMENTS_VALUES_DETAIL_LIBELLE]; //Contrat libelle
                    $ligne['produit_type'] = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
                    break;
                }
            case 'sorties/': {
                    $ligne['mouvement_type'] = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT;
                    $ligne['contrat_identifiant'] = $f->value[FactureClient::MOUVEMENTS_VALUES_TYPE_TRANS]; //Contrat id
                    $ligne['contrat_libelle'] = $f->value[FactureClient::MOUVEMENTS_VALUES_DETAIL_LIBELLE]; //Contrat libelle
                    $ligne['produit_type'] = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
                    break;
                }
            default:
                $ligne['mouvement_type'] = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE;
                break;
        }
        return $ligne;
    }

    public function findByIdentifiant($identifiant) {
        return $this->find('FACTURE-' . $identifiant);
    }
    
    public function findByEtablissementAndId($idEtablissement, $idFacture) {
        return $this->find('FACTURE-' . $idEtablissement . '-' . $idFacture);
    }

    public function getMouvementsForMasse($regions) {
        if(!$regions){
            return DRMMouvementsFactureView::getInstance()->getMouvementsFacturables(0, 1);
        }
        $mouvementsByRegions = array();
        foreach ($regions as $region) {
            $mouvementsByRegions = array_merge(DRMMouvementsFactureView::getInstance()->getMouvementsFacturablesByRegions(0, 1,$region),$mouvementsByRegions);
        }
        return $mouvementsByRegions;    
    }
    
    public function getMouvementsNonFacturesMasse() {
        
    }

    public function filterWithParameters($mouvementsByEtb, $parameters) {
        foreach ($mouvementsByEtb as $k => $mouvements) {
            foreach ($mouvements as $key => $mouvement) {
                if (isset($parameters['date_mouvement']) && ($parameters['date_mouvement'] != '') &&
                        ($this->supEqDate($mouvement->value[FactureClient::MOUVEMENTS_VALUES_DATE], $parameters['date_mouvement']))) {
                    unset($mouvements[$key]);
                }
            }
            if (count($mouvements) == 0) {
                unset($mouvementsByEtb[$k]);
            } else {
                $mouvementsByEtb[$k] = $mouvements;
            }
        }
        foreach ($mouvementsByEtb as $key => $mouvements) {
            $somme = 0;
            //perturbant? 2 niveau de filtre ici => facture?
            foreach ($mouvements as $mouvement) {
                $somme += $mouvement->value[FactureClient::MOUVEMENTS_VALUES_VOLUME] * $mouvement->value[FactureClient::MOUVEMENTS_VALUES_CVO];
            }
            $somme = abs($somme);
            $somme = $this->ttc($somme);
            if (isset($parameters['seuil']) && $parameters['seuil'] != '') {
                if ($somme >= $parameters['seuil']) {
                    unset($mouvementsByEtb[$key]);
                }
            }
        }
        if (count($mouvementsByEtb) == 0)
            return null;
        return $mouvementsByEtb;
    }

    private function supEqDate($date_0, $date_1) {
        $date_0 = str_replace('-', '', $date_0);
        $date_1Arr = explode('/', $date_1);
        
        return $date_0 >= ($date_1Arr[2] . $date_1Arr[1] . $date_1Arr[0]);
    }

    public function getMouvementsNonFacturesByEtb($mouvements) {

        $generationFactures = array();
        foreach ($mouvements as $mouvement) {
            if (array_key_exists($mouvement->key[FactureClient::MOUVEMENTS_KEYS_ETB_ID], $generationFactures)) {
                $generationFactures[$mouvement->key[FactureClient::MOUVEMENTS_KEYS_ETB_ID]][] = $mouvement;
            } else {
                $generationFactures[$mouvement->key[FactureClient::MOUVEMENTS_KEYS_ETB_ID]] = array();
                $generationFactures[$mouvement->key[FactureClient::MOUVEMENTS_KEYS_ETB_ID]][] = $mouvement;
            }
        }
        return $generationFactures;
    }

    public function createFacturesByEtb($generationFactures,$date_facturation) {

        $generation = new Generation();
        $generation->date_emission = date('Y-m-d-H:i');
        $generation->type_document = 'Facture';
        $generation->documents = array();
        $generation->somme = 0;
        $cpt = 0;

        foreach ($generationFactures as $etablissementID => $mouvementsEtb) {
            $etablissement = EtablissementClient::getInstance()->findByIdentifiant($etablissementID);
            $f = $this->createDoc($mouvementsEtb, $etablissement, $date_facturation);
            
            $f->save();

            $generation->somme += $f->total_ttc;
            $generation->add('documents')->add($cpt, $f->_id);
            $cpt++;
        }

        return $generation;
    }

    public function getMouvementsNonFacturesByEtablissement($etablissement) {

        return FactureMouvementsDRMView::getInstance()->getFacturationByEtablissement($etablissement, 0, 1);
    }

    public function findByEtablissement($etablissement) {
        return acCouchdbManager::getClient()
                        ->startkey(array($etablissement->_id))
                        ->endkey(array($etablissement->_id, array()))
                        ->getView("facture", "etablissement")
                ->rows;
    }

    public function getSomme($facture) {
        $facture->montant_tcc;
    }

    private function ttc($p) {
        return $p + $p * 0.196;
    }

    public function getTypes() {
        return array(FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS);
    }
    
    public function hasCritereInHashedLignes($hashTable,$mouvement_type,$produit_type='',$produit_hash='',$origine_identifiant='')
    {
        $keys = array_keys($hashTable);
        foreach ($keys as $hashKey){
            if(strpos($hashKey,'#'.$mouvement_type.$produit_type.$produit_hash.$origine_identifiant.'#')===0)
            {
                return true;
            }
        }
        return false;
    }
    
    public function getTypesTransactionsFromHashedLignes($hashTable,$mouvement_type)
    {
        $typesTransactions = array();
        $keys = array_keys($hashTable);
        foreach ($keys as $hashKey) {
            if(strpos($hashKey,'#'.$mouvement_type.'#')===0)
            {
                if(!in_array($hashTable[$hashKey]->produit_type, $typesTransactions)) $typesTransactions[] = $hashTable[$hashKey]->produit_type;
            }
        }
        return $typesTransactions;
    }
    
    public function getProduitsFromHashedLignes($hashTable,$mouvement_type,$produit_type)
    {
        $produits = array();
        $keys = array_keys($hashTable);
        foreach ($keys as $hashKey) {
            if(strpos($hashKey,'#'.$mouvement_type.'#'.$produit_type.'#')===0)
            {
                if(!array_key_exists($hashTable[$hashKey]->produit_hash, $produits)) 
                        $produits[$hashTable[$hashKey]->produit_hash] = $hashTable[$hashKey]->produit_libelle;
            }
        }
        return $produits;
    }
    public function getOriginsFromHashedLignes($hashTable,$mouvement_type,$produit_type,$produit_hash)
    {
        $origines = array();
        $keys = array_keys($hashTable);
        foreach ($keys as $hashKey) {
            if(strpos($hashKey,'#'.$mouvement_type.'#'.$produit_type.'#'.$produit_hash.'#')===0)
            {
                
                        $origines[$hashKey] = $hashTable[$hashKey];
            }
        }
        return $origines;
    }
    
    public function countNbLignes($facture,$hashTable) {
        
        $nbLigne = count($facture->echeances) * 3;
        
        if($this->hasCritereInHashedLignes($hashTable,self::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE)) 
        {
           $nbLigne++;
           $produits = $this->getProduitsFromHashedLignes($hashTable,
                                                          self::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE,
                                                          self::FACTURE_LIGNE_PRODUIT_TYPE_VINS);
           foreach ($produits as $prodHash => $produit)
           {
              $nbLigne++; 
              $docOrigins = $this->getOriginsFromHashedLignes($hashTable,
                                                              self::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE,
                                                              self::FACTURE_LIGNE_PRODUIT_TYPE_VINS,
                                                              $prodHash);
              $nbLigne += count($docOrigins);
           }
        }   
        if($this->hasCritereInHashedLignes($hashTable,self::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT))
        {
           $types = $this->getTypesTransactionsFromHashedLignes($hashTable,self::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT);                    
           foreach ($types as $type){
               $nbLigne++;               
               $produits = $this->getProduitsFromHashedLignes($hashTable,
                                                              self::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT,
                                                              $type);                        
               foreach ($produits as $prodHash => $produit)
               {
                $docOrigins = $this->getOriginsFromHashedLignes($hashTable,
                                                                self::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT,
                                                                $type,
                                                                $prodHash);
                $nbLigne+=count($docOrigins)+1;
               }
           }
        }
        return $nbLigne;
    }
    
}
