<?php
class FactureClient extends acCouchdbClient {

    const FACTURE_LIGNE_ORIGINE_TYPE_DRM = "DRM";
    const FACTURE_LIGNE_ORIGINE_TYPE_SV = "SV12";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE = "propriete";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT = "contrat";
    const FACTURE_LIGNE_PRODUIT_TYPE_VINS = "contrat_vins";
    const FACTURE_LIGNE_PRODUIT_TYPE_MOUTS = "contrat_mouts";
    const FACTURE_LIGNE_PRODUIT_TYPE_RAISINS = "contrat_raisins";
    
    const STATUT_REDRESSEE = 'redressee';

    public static function getInstance() {
        return acCouchdbManager::getClient("Facture");
    }

    public function getNextNoFacture($idClient,$date)
    {   
        $id = '';
    	$facture = self::getAtDate($idClient,$date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($facture) > 0) {
            $id .= ((double)str_replace('FACTURE-'.$idClient.'-', '', max($facture)) + 1);
        } else {
            $id.= $date.'01';
        }
        return $id;
    }
    
    public function getAtDate($idClient,$date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('FACTURE-'.$idClient.'-'.$date.'00')->endkey('FACTURE-'.$date.'99')->execute($hydrate);        
    }

    public function getFacturationForEtablissement($etablissement, $level) {
        return MouvementFacturationView::getInstance()->getMouvementsByEtablissementWithReduce($etablissement, 0, 1, $level);
    }

    public function createDoc($factures, $etablissement, $date_facturation = null) {

        $facture = new Facture();
        $facture->date_emission = date('Y-m-d');
        $facture->date_facturation = $date_facturation;
        if (!$facture->date_facturation)
            $facture->date_facturation = date('Y-m-d');
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
        $facture->origines = array();
        
        $famille = $etablissement->famille;
        
        $montant_ht = 0;
        $origines = array();
        foreach ($factures as $lignesByType) {
            $montant_ht += $this->createFactureLigne($lignesByType, $facture,$famille);
        }
        $facture->origines = $origines;
        $this->createFacturePapillons($facture);
        $facture->total_ht = $montant_ht;
        $facture->total_ttc = $this->ttc($facture->total_ht);
        $facture->origines = $this->createOrigines($facture);
        return $facture;
    }

    private function createFactureLigne($lignesByType, $facture,$famille) {
        $cvo = $lignesByType->value[MouvementFacturationView::VALUE_CVO];
        $montant_ht = $cvo * $lignesByType->value[MouvementFacturationView::VALUE_VOLUME] * -1;
        $volume = $lignesByType->value[MouvementFacturationView::VALUE_VOLUME];

        $ligneObj = $facture->lignes->add($lignesByType->key[MouvementFacturationView::KEYS_MATIERE])->add();
        $ligneObj->origine_type = $lignesByType->key[MouvementFacturationView::KEYS_ORIGIN];        
        $ligneObj->origine_identifiant = $lignesByType->value[MouvementFacturationView::VALUE_NUMERO]; 
        $ligneObj->contrat_identifiant = $lignesByType->key[MouvementFacturationView::KEYS_CONTRAT_ID];        
        $ligneObj->origine_date = $lignesByType->key[MouvementFacturationView::KEYS_PERIODE];
        $ligneObj->produit_type = $lignesByType->key[MouvementFacturationView::KEYS_MATIERE];
        $ligneObj->produit_libelle = $lignesByType->value[MouvementFacturationView::VALUE_PRODUIT_LIBELLE];
        $ligneObj->produit_hash = $lignesByType->key[MouvementFacturationView::KEYS_PRODUIT_ID];
        $ligneObj->volume = $volume;
        $ligneObj->cotisation_taux = $cvo;
        $ligneObj->montant_ht = $montant_ht;
        $ligneObj->origine_mouvements = $this->createLigneOriginesMouvements($lignesByType->value[MouvementFacturationView::VALUE_ID_ORIGINE]);
        
        $ligneObj->origine_libelle = $this->createOrigineLibelle($ligneObj,$lignesByType,$famille);
        return $montant_ht;
    }

    private function createLigneOriginesMouvements($originesTable) {
        $origines = array();
        foreach ($originesTable as $origineFormatted) {
            $origineKeyValue = explode(':', $origineFormatted);
            if(count($origineKeyValue)!=2) throw new Exception('Le mouvement est mal formé : %s',  print_r($origineKeyValue));
            $key = $origineKeyValue[0];
            $value = $origineKeyValue[1];
            if(!array_key_exists($key, $origines))
            {
                $origines[$key] = array();
            }
            $origines[$key][] = $value;            
        }
        return $origines;
    }
    
    private function createOrigineLibelle($ligneObj,$lignesByType,$famille) {     
        if($ligneObj->origine_type == self::FACTURE_LIGNE_ORIGINE_TYPE_SV){
            $origine_libelle = 'Contrat du '.$this->formatContratNum($ligneObj->contrat_identifiant);
            $origine_libelle .= ' ('.$lignesByType->value[MouvementFacturationView::VALUE_VRAC_DEST].') ';
            if($famille==EtablissementFamilles::FAMILLE_NEGOCIANT)
                $origine_libelle .= SV12Client::getInstance()->getLibelleFromIdSV12($ligneObj->origine_identifiant);
            return $origine_libelle;
        }
        
        if($ligneObj->origine_type == self::FACTURE_LIGNE_ORIGINE_TYPE_DRM){
            if($ligneObj->produit_type == self::FACTURE_LIGNE_PRODUIT_TYPE_VINS)
            {
                $origine_libelle = 'Contrat du '.$this->formatContratNum($ligneObj->contrat_identifiant);
                $origine_libelle .= ' ('.$lignesByType->value[MouvementFacturationView::VALUE_VRAC_DEST].') ';
                if($famille==EtablissementFamilles::FAMILLE_PRODUCTEUR)
                    $origine_libelle .= DRMClient::getInstance()->getLibelleFromIdDRM($ligneObj->origine_identifiant);
                return $origine_libelle;
            }
            return DRMClient::getInstance()->getLibelleFromIdDRM($ligneObj->origine_identifiant);
        }
    }
    
    private function formatContratNum($id)
    {
        if(strlen($id)!=13) throw new Exception(sprintf ('Le numéro de contrat %s ne possède pas un bon format.',$id));
        $annee = substr($id, 0,4);
        $mois = substr($id, 4,2);
        $jour = substr($id, 6,2);
        $num = substr($id, 8);
        return $jour.'/'.$mois.'/'.$annee.' n°'.$num;
    }   
    
    private function createFacturePapillons($facture) {
        foreach ($facture->lignes as $typeLignes) {
            foreach ($typeLignes as $ligne) {

                switch ($ligne->produit_type) {
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

    public function findByIdentifiant($identifiant) {
        return $this->find('FACTURE-' . $identifiant);
    }

    public function findByEtablissementAndId($idEtablissement, $idFacture) {
        return $this->find('FACTURE-' . $idEtablissement . '-' . $idFacture);
    }

    public function createOrigines($facture)
    {
        $origines = array();
        foreach ($facture->getLignes() as $lignesType) {
            foreach ($lignesType as $ligne) {
                if(!array_key_exists($ligne->origine_identifiant, $origines))
                        $origines[$ligne->origine_identifiant] = $ligne->origine_identifiant;
            }
        }
        return $origines;
    }

    public function getMouvementsForMasse($regions,$level) {
        if(!$regions){
            return MouvementFacturationView::getInstance()->getMouvements(0, 1,$level);
        }
        $mouvementsByRegions = array();
        foreach ($regions as $region) {
            $mouvementsByRegions = array_merge(MouvementFacturationView::getInstance()->getMouvementsFacturablesByRegions(0, 1,$region,$level),$mouvementsByRegions);
        }
        return $mouvementsByRegions;    
    }
    
    public function getMouvementsNonFacturesByEtb($mouvements) {

        $generationFactures = array();
        foreach ($mouvements as $mouvement) {
            if (array_key_exists($mouvement->key[MouvementFacturationView::KEYS_ETB_ID], $generationFactures)) {
                $generationFactures[$mouvement->key[MouvementFacturationView::KEYS_ETB_ID]][] = $mouvement;
            } else {
                $generationFactures[$mouvement->key[MouvementFacturationView::KEYS_ETB_ID]] = array();
                $generationFactures[$mouvement->key[MouvementFacturationView::KEYS_ETB_ID]][] = $mouvement;
            }
        }
        return $generationFactures;
    }
    
    public function filterWithParameters($mouvementsByEtb, $parameters) {
        
    if (isset($parameters['date_mouvement']) && ($parameters['date_mouvement'] != '')){
        foreach ($mouvementsByEtb as $identifiant => $mouvements) {
            foreach ($mouvements as $key => $mouvement) {
                    if($this->supEqDate($mouvement->value[MouvementFacturationView::VALUE_DATE], $parameters['date_mouvement'])) {
                        unset($mouvements[$key]);
                    }
            }
        }
    }
    //Si seuil il y a
    if (isset($parameters['seuil']) && $parameters['seuil'] != '') {
        foreach ($mouvementsByEtb as $identifiant => $mouvements) {
            $somme = 0;
            foreach ($mouvements as $mouvement) {
                $somme+= $mouvement->value[MouvementFacturationView::VALUE_VOLUME] * $mouvement->value[MouvementFacturationView::VALUE_CVO];
            }
            $somme = abs($somme);
            $somme = $this->ttc($somme);
            exit;
            if ($somme >= $parameters['seuil']) {
                    unset($mouvementsByEtb[$identifiant]);
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

    private function ttc($p) {
        return $p + $p * 0.196;
    }

    public function getTypes() {
        return array(FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS,
            FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS);
    }

    public function getProduitsFromTypeLignes($lignes) {
        $produits = array();
        foreach ($lignes as $ligne) {
            if (array_key_exists($ligne->produit_hash, $produits)) {
                $produits[$ligne->produit_hash][] = $ligne;
            } else {
                $produits[$ligne->produit_hash] = array();
                $produits[$ligne->produit_hash][] = $ligne;
            }
        }
        return $produits;
    }

    public function isRedressee($statut){
        return ($statut == self::STATUT_REDRESSEE);
    }
        
    public function getTypeLignePdfLibelle($typeLibelle) {
      if ($typeLibelle == self::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE)
	return 'propriété';
      switch ($typeLibelle) {
      case self::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS:
	return 'contrats moûts';
	
      case self::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS:
	return 'contrats raisins';
	
      case self::FACTURE_LIGNE_PRODUIT_TYPE_VINS:
	return 'contrats vins';
      }
      return '';
    }

    public function defactureCreateAvoirAndSaveThem(Facture $f) {
      $avoir = clone $f;
      foreach($avoir->lignes as $type => $lignes) {
	foreach($lignes as $id => $ligne) {
	  $ligne->volume *= -1;
	  $ligne->montent_ht *= -1;
	}
      }
      $avoir->montant_ttc *= -1;
      $avoir->remove('echeance');
      $avoir->add('echeance');
      $avoir->save();
      $f->defacturer();
      $f->save();
    }
    
}
