<?php

class FactureClient extends acCouchdbClient {

    const FACTURE_LIGNE_ORIGINE_TYPE_DRM = "DRM";
    const FACTURE_LIGNE_ORIGINE_TYPE_SV = "SV";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE = "Propriete";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT = "Contrat";
    const FACTURE_LIGNE_PRODUIT_TYPE_VINS = "Vins";
    const FACTURE_LIGNE_PRODUIT_TYPE_MOUTS = "Mouts";
    const FACTURE_LIGNE_PRODUIT_TYPE_RAISINS = "Raisins";
    const MOUVEMENTS_KEYS_FACTURE = 0;
    const MOUVEMENTS_KEYS_FACTURABLE = 1;
    const MOUVEMENTS_KEYS_ETB_ID = 2;
    const MOUVEMENTS_KEYS_ORIGIN = 3;
    const MOUVEMENTS_KEYS_PERIODE = 4;
    const MOUVEMENTS_KEYS_MATIERE = 5;
    const MOUVEMENTS_KEYS_PRODUIT_ID = 6;
    const MOUVEMENTS_KEYS_MVT_TYPE = 7;
    const MOUVEMENTS_VALUES_PRODUIT_NAME = 0;
    const MOUVEMENTS_VALUES_TYPE_TRANS = 1;
    const MOUVEMENTS_VALUES_VOLUME = 2;
    const MOUVEMENTS_VALUES_DETAIL_LIBELLE = 3;
    const MOUVEMENTS_VALUES_CVO = 4;
    const MOUVEMENTS_VALUES_ID = 5;

    public static function getInstance() {
        return acCouchdbManager::getClient("Facture");
    }

    public function getId($client_reference, $identifiant) {
        return 'FACTURE-' . $client_reference . '-' . $identifiant;
    }

    public function createDoc($factures, $etablissement) {
        $facture = new Facture();
        $numPage = '01';
        $facture->identifiant = date('Ymd') . $numPage;
        $facture->date_emission = date('Y-m-d');
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
            $current_ligne = $this->createFactureLigne($f,$facture);
            $facture->add("lignes")->add($cptLigne, $current_ligne);
            $origines[$f->value[FactureClient::MOUVEMENTS_VALUES_ID]] = $f->value[FactureClient::MOUVEMENTS_VALUES_ID];
    //         = array('DRM-123-2012-01' => 'DRM de janvier', 'DRM-123-2011-12' => 'DRM de décembre');            
            $cptLigne++;
        }
        $facture->origines = $origines;
        

        $this->createFacturePapillons($facture);
        
        $facture->total_ttc = $this->ttc($facture->total_ht);
        return $facture;
    }

    private function createFactureLigne($f,$facture) {        
        $f->value[FactureClient::MOUVEMENTS_VALUES_VOLUME] = -1 * $f->value[FactureClient::MOUVEMENTS_VALUES_VOLUME];
        
        $cvo = $f->value[FactureClient::MOUVEMENTS_VALUES_VOLUME] * $f->value[FactureClient::MOUVEMENTS_VALUES_CVO];
        $ligne = array('origine_type' => $f->key[FactureClient::MOUVEMENTS_KEYS_ORIGIN],
                       'origine_identifiant' => $f->value[FactureClient::MOUVEMENTS_VALUES_ID],
                       'origine_date' => $f->key[FactureClient::MOUVEMENTS_KEYS_PERIODE],
                       'produit_type' => $f->key[FactureClient::MOUVEMENTS_KEYS_MATIERE],
                       'produit_libelle' => $f->value[FactureClient::MOUVEMENTS_VALUES_PRODUIT_NAME],
                       'produit_hash' => $f->key[FactureClient::MOUVEMENTS_KEYS_PRODUIT_ID],
                       'volume' => $f->value[FactureClient::MOUVEMENTS_VALUES_VOLUME],
                       'cotisation_taux' => $f->value[FactureClient::MOUVEMENTS_VALUES_CVO],
                       'montant_ht' => $cvo);
        $facture->total_ht += $cvo;
        
        $ligne = $this->createFactureLigneContrat($ligne,$f);

        return $ligne;
    }

    private function createFacturePapillons($facture)
    {
        foreach ($facture->lignes as $ligne)
        {
            switch ($ligne['produit_type']) {
                case FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS:
                case FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS:
                    if(strstr($ligne['produit_hash'],'mentions/SL/'))
                    {
                        $this->createOrUpdateEcheanceC($ligne,$facture);
                    }
                    else
                    {
                        $this->createOrUpdateEcheanceB($ligne,$facture);
                    }
                break;
                default :
                    $this->createOrUpdateEcheanceA($ligne,$facture);
                break;
            }
        }
    }
    
    private function createOrUpdateEcheanceA($l,$facture)
    {
            $l['echeance_code'] = 'A';  
            $date = date('Y-m-d', strtotime("+60 days"));
            $montant_ttc = $this->ttc($l['montant_ht']);
            $this->updateEcheance('A',$date,$montant_ttc,$facture);
    }
    
    private function createOrUpdateEcheanceB($l,$facture)
    {
            $l['echeance_code'] = 'B';
            $date = date('Ymd');
            $d1 = date('Y', strtotime("-1 years")).'0801'; // 01/08/N-1
            $d2 = date('Y').'0331'; // 31/03/N
            $d3 = date('Y').'0531'; // 31/05/N    
            //        
//          if(01/08/N-1 < date < 31/03/N) { 50% au 31/03 et 50% au 31/05 }            
            if(($d1 < $date) && ($date < $d2))
            {
                $montant_ttc = $this->ttc($l['montant_ht']) * 0.5;
                $dateEcheance1 = date('Y').'-03-31';              
                $this->updateEcheance('B',$dateEcheance1,$montant_ttc,$facture);
                
                $dateEcheance2 = date('Y').'-05-31';              
                $this->updateEcheance('B',$dateEcheance2,$montant_ttc,$facture);
                return;
            }
            
//          if(01/04/N < date < 31/05/N)   { 50% comptant et  50% au 31/05 }              
            if(($d2 < $date) && ($date <= $d3))
            {
                $montant_ttc = $this->ttc($l['montant_ht']) * 0.5;
                $dateEcheance1 = date('Y-m-d');              
                $this->updateEcheance('B',$dateEcheance1,$montant_ttc,$facture);
                
                $dateEcheance2 = date('Y').'-05-31';              
                $this->updateEcheance('B',$dateEcheance2,$montant_ttc,$facture);
                return;
            }

//            if(date > 31/05/N) { 100% comptant } 
          if($date > $d3)
            {            
                $this->updateEcheance('B',date('Y-m-d'),$this->ttc($l['montant_ht']),$facture);
                return;
            }
            
    }
    
    private function createOrUpdateEcheanceC($l,$facture)
    {
            $l['echeance_code'] = 'C';
            $date = date('Y').'-09-30';
            $montant_ttc = $this->ttc($l['montant_ht']);
            $this->updateEcheance('C',$date,$montant_ttc,$facture);
         
    }
    
    private function updateEcheance($echeance_code,$date,$montant_ttc,$facture) {        
            $Aexist = false;
            foreach ($facture->echeances as $e)
            {
                if(($e['echeance_code'] == $echeance_code) && ($e['echeance_date'] == $date))
                {
                   $e['montant_ttc'] +=  $montant_ttc;
                   $Aexist = true;
                   break;
                }
            }
            if(!$Aexist)
            {
                $echeance = array();
                $echeance['echeance_code'] = $echeance_code;
                $echeance['montant_ttc'] = $montant_ttc;
                $echeance['echeance_date'] = $date;
                $facture->add("echeances")->add(count($facture->echeances), $echeance);
            }
    }
    
    private function createFactureLigneContrat($ligne,$f) {
        $ligne['contrat_identifiant'] = '';
        $ligne['contrat_libelle'] = '';
        
        // CONTRAT-XXXXXXX-XXXX si mouvement contrat
        //le libellé du contrat tel qu'il apparait sur la facture genre "n° XXXXXX du JJ/MM/AAAA
        
        switch ($f->key[FactureClient::MOUVEMENTS_KEYS_MVT_TYPE]) {
            case 'sorties/vrac':
                {
                    $ligne['mouvement_type'] = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT;
                    $ligne['contrat_identifiant'] = $f->value[FactureClient::MOUVEMENTS_VALUES_TYPE_TRANS]; //Contrat id
                    $ligne['contrat_libelle'] = $f->value[FactureClient::MOUVEMENTS_VALUES_DETAIL_LIBELLE]; //Contrat libelle
                    $ligne['produit_type'] = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
                    break;
                }
            case 'sorties/':
                {
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

    public function findByEtablissementAndId($idEtablissement, $idFacture) {
        return $this->find('FACTURE-' . $idEtablissement . '-' . $idFacture);
    }

    public function findByEtablissement($etablissement) {
        return acCouchdbManager::getClient()
                        ->startkey(array($etablissement->_id))
                        ->endkey(array($etablissement->_id, array()))
                        ->getView("facture", "etablissement")
                ->rows;
    }

    public function getMouvementsNonFacturesByEtablissement($etablissement) {

        return DRMMouvementsFactureView::getInstance()->getFacturationByEtablissement($etablissement, 0, 1);
    }

    private function ttc($p) {
        return $p + $p * 0.196;
    }

}
