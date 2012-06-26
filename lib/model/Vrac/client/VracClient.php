<?php

class VracClient extends acCouchdbClient {
   
    
    const VRAC_VIEW_STATUT = 0;
    const VRAC_VIEW_NUMCONTRAT = 1;
    const VRAC_VIEW_VENDEUR_ID = 2;
    const VRAC_VIEW_VENDEUR_NOM = 3;
    const VRAC_VIEW_ACHETEUR_ID = 4;
    const VRAC_VIEW_ACHETEUR_NOM = 5;
    const VRAC_VIEW_MANDATAIRE_ID = 6;
    const VRAC_VIEW_MANDATAIRE_NOM = 7;    
    const VRAC_VIEW_TYPEPRODUIT = 8;
    const VRAC_VIEW_PRODUIT_ID = 9;
    const VRAC_VIEW_VOLCONS = 10;
    const VRAC_VIEW_VOLENLEVE = 11;

    const VRAC_SIMILAIRE_KEY_VENDEURID = 'vendeur_identifiant';   
    const VRAC_SIMILAIRE_KEY_ACHETEURID = 'acheteur_identifiant';
    const VRAC_SIMILAIRE_KEY_MANDATAIREID = 'mandataire_identifiant'; 
    const VRAC_SIMILAIRE_KEY_PRODUIT = 'produit';
    const VRAC_SIMILAIRE_KEY_TYPE = 'type_transaction';
    const VRAC_SIMILAIRE_KEY_VOLPROP = 'volume_propose';
    const VRAC_SIMILAIRE_KEY_ETAPE = 'etape';
    
    const VRAC_SIMILAIRE_VALUE_NUMCONTRAT = 0;   
    const VRAC_SIMILAIRE_VALUE_STATUT = 1;
    
    
    const TYPE_TRANSACTION_RAISINS = 'raisins';
    const TYPE_TRANSACTION_MOUTS = 'mouts';
    const TYPE_TRANSACTION_VIN_VRAC = 'vin_vrac';
    const TYPE_TRANSACTION_VIN_BOUTEILLE = 'vin_bouteille';

    const TYPE_CONTRAT_SPOT = 'spot';
    const TYPE_CONTRAT_PLURIANNUEL = 'pluriannuel';

    const CVO_NATURE_MARCHE_DEFINITIF = 'marche_definitif';
    const CVO_NATURE_COMPENSATION = 'compensation';
    const CVO_NATURE_NON_FINANCIERE = 'non_financiere';
    const CVO_NATURE_VINAIGRERIE = 'vinaigrerie';
    
    const STATUS_CONTRAT_SOLDE = 'SOLDE';
    const STATUS_CONTRAT_ANNULE = 'ANNULE';
    const STATUS_CONTRAT_NONSOLDE = 'NONSOLDE';
    
    
    public static $contenance = array('75 cl' => 0.0075,
                                   '1 L' => 0.01,
                                     '1.5 L'=> 0.015,
                                     '3 L' => 0.03,
                                        'BIB 3 L' => 0.03,
                                    '6 L' => 0.06);

    
    

    /**
     *
     * @return DRMClient
     */
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Vrac");
    }

    public function getId($numeroContrat)
    {
      return 'VRAC-'.$numeroContrat;
    }

    public function getNextNoContrat()
    {   
        $id = '';
    	$date = date('Ymd');
    	$contrats = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($contrats) > 0) {
            $id .= ((double)str_replace('VRAC-', '', max($contrats)) + 1);
        } else {
            $id.= $date.'0001';
        }

        return $id;
    }
    
    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('VRAC-'.$date.'0000')->endkey('VRAC-'.$date.'9999')->execute($hydrate);        
    }
    
    public function findByNumContrat($num_contrat) {
      return $this->find($this->getId($num_contrat));
    }
    
    public function retrieveLastDocs() {
      return $this->descending(true)->limit(300)->getView('vrac', 'history');
    }
    
    public function retrieveBySoussigne($soussigneParam) {
      return $this->startkey(array($soussigneParam))
              ->endkey(array($soussigneParam, array()))->limit(300)->getView('vrac', 'soussigneidentifiant');
    }
    
    public function retrieveSimilaryContracts($params) {
       /* if($params['etape']==)
        {    */
            return $this->startkey(array($params['vendeur'],$params['acheteur'],$params['mandataire']))
                   ->endkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'], array()))->limit(10)->getView('vrac', 'vracSimilaire');
        /*}
        else
        {
            
            return $this->startkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['produit'],$params['type'],$params['volume']*.95))
                   ->endkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['produit'],$params['type'],$params['volume']*1.05, array()))->limit(10)->getView('vrac', 'vracSimilaire');
        }      */ 
            
    }
    
 }
