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
    const VRAC_VIEW_VOLPROP = 13;
    const VRAC_VIEW_VOLENLEVE = 14;

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

    
    public static $types_transaction_vins = array(self::TYPE_TRANSACTION_VIN_VRAC, self::TYPE_TRANSACTION_VIN_BOUTEILLE);

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

    public function buildCampagne($date) {

      return ConfigurationClient::getInstance()->buildCampagne($date);
    }

    public function getNextNoContrat()
    {   
        $id = '';
    	$date = date('Ymd');
    	$contrats = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($contrats) > 0) {
            $id .= ((double)str_replace('VRAC-', '', max($contrats)) + 1);
        } else {
            $id.= $date.'00001';
        }

        return $id;
    }
    
    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('VRAC-'.$date.'00000')->endkey('VRAC-'.$date.'99999')->execute($hydrate);        
    }
    
    public function findByNumContrat($num_contrat) {
      return $this->find($this->getId($num_contrat));
    }
    
    public function retrieveLastDocs($limit = 300) {
      return $this->descending(true)->limit($limit)->getView('vrac', 'history');
    }
    
    public function retrieveBySoussigne($soussigneId,$campagne,$limit=300) {
      $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
      if (!preg_match('/[0-9]*-[0-9]*/', $campagne)) 
	throw new sfException("wrong campagne format ($campagne)");
      $bySoussigneQuery = $this->startkey(array('STATUT',$soussigneId, $campagne))
	->endkey(array('STATUT',$soussigneId, $campagne, array()));
      if ($limit){
            $bySoussigneQuery =  $bySoussigneQuery->limit($limit);
        }
      
      $bySoussigne = $bySoussigneQuery->getView('vrac', 'soussigneidentifiant');
      return $bySoussigne;
    }
    
    public function retrieveByType($type,$campagne,$limit=300) {
      $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
      if (!preg_match('/[0-9]*-[0-9]*/', $campagne)) 
	throw new sfException("wrong campagne format ($campagne)");
      $bySoussigneTypeQuery = $this->startkey(array('TYPE',$soussigneId,$campagne, $type))
	->endkey(array('TYPE',$soussigneId,$campagne,$type, array()));
    
      if ($limit){
	$bySoussigneTypeQuery =  $bySoussigneTypeQuery->limit($limit);
      }
      $bySoussigneType = $bySoussigneTypeQuery->getView('vrac', 'soussigneidentifiant');
      return $bySoussigneType;
    }
    
    public function retrieveBySoussigneAndStatut($soussigneId,$campagne,$statut,$limit=300) {
      $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
      if (!preg_match('/[0-9]*-[0-9]*/', $campagne)) 
	throw new sfException("wrong campagne format ($campagne)");
      $bySoussigneStatutQuery =  $this->startkey(array('STATUT',$soussigneId,$campagne,$statut))
	->endkey(array('STATUT',$soussigneId,$campagne,$statut, array()));

      if ($limit){
	$bySoussigneStatutQuery =  $bySoussigneStatutQuery->limit($limit);
      }
      
      $bySoussigneStatut = $bySoussigneStatutQuery->getView('vrac', 'soussigneidentifiant');
      return $bySoussigneStatut;
    }
    
    public function retrieveBySoussigneAndType($soussigneId,$campagne,$type,$limit=300) {
      if (!preg_match('/[0-9]*-[0-9]*/', $campagne)) 
	throw new sfException("wrong campagne format ($campagne)");
      $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
      $bySoussigneTypeQuery = $this->startkey(array('TYPE',$soussigneId,$campagne,$type))
	->endkey(array('TYPE',$soussigneId,$campagne,$type, array()));
      
      if ($limit){
	$bySoussigneTypeQuery =  $bySoussigneTypeQuery->limit($limit);
      }
      $bySoussigneType = $bySoussigneTypeQuery->getView('vrac', 'soussigneidentifiant');
      return $bySoussigneType;
    }
    
    public function retrieveBySoussigneStatutAndType($soussigneId,$campagne,$statut,$type,$limit=300) {
      if (!preg_match('/[0-9]*-[0-9]*/', $campagne)) 
	throw new sfException("wrong campagne format ($campagne)");
      $soussigneId = EtablissementClient::getInstance()->getIdentifiant($soussigneId);
      $bySoussigneTypeQuery = $this->startkey(array('STATUT',$soussigneId,$campagne,$statut,$type))
	->endkey(array('STATUT',$soussigneId,$campagne, $statut,$type, array()));
      
      if ($limit){
              $bySoussigneTypeQuery =  $bySoussigneTypeQuery->limit($limit);
          }
      $bySoussigneType = $bySoussigneTypeQuery->getView('vrac', 'soussigneidentifiant');
      return $bySoussigneType;
    }

    public function getCampagneByIdentifiant($identifiant) {
      $rows = $this->startkey(array('STATUT', $identifiant))
	->startkey(array('STATUT', $identifiant, array()))
	->limit(1)->getView('vrac', 'soussigneidentifiant')->rows;
      return array($rows[0]->key[2] => $rows[0]->key[2]);
    }

    public static function getCsvBySoussigne($vracs)
    {
        $result ="\xef\xbb\xbf";
        foreach ($vracs->rows as $value)
        {   
            $cpt=0;
            $elt = $value->getRawValue()->value;
            
            foreach ($elt as $key => $champs)
            {
                $cpt++;
                if(($key == self::VRAC_VIEW_PRODUIT_ID) && ($champs!= ""))
                   $champs = ConfigurationClient::getCurrent()->get($champs)->libelleProduit(array(),"%c% %g% %a% %m% %l% %co% %ce% %la%");                   
                $result.='"'.$champs.'"';
                if($cpt < count($elt)) $result.=';';              
            }
            $result.="\n";
        }
        return $result;
    }

    public function retrieveSimilaryContracts($vrac) {       
        if(isset($vrac->vendeur_identifiant) || isset($vrac->acheteur_identifiant)) {            
        	return false;
    	} 
        if(is_null($vrac->produit)){            
                return 
                ($vrac->mandataire_exist)?
                    $this->startkey(array('M',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->mandataire_identifiant,$vrac->type_transaction))
                         ->endkey(array('M',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->mandataire_identifiant,$vrac->type_transaction, array()))->limit(10)->getView('vrac', 'vracSimilaire')
                  : $this->startkey(array('',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->type_transaction))
                         ->endkey(array('',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->type_transaction, array()))->limit(10)->getView('vrac', 'vracSimilaire');
        
                            
                            
        }
        if(is_null($vrac->volume_propose)){
                return 
                ($vrac->mandataire_exist)?
                    $this->startkey(array('M',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->mandataire_identifiant,$vrac->type_transaction,$vrac->produit))
                         ->endkey(array('M',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->mandataire_identifiant,$vrac->type_transaction,$vrac->produit, array()))->limit(10)->getView('vrac', 'vracSimilaire')
                  : $this->startkey(array('',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->type_transaction,$vrac->produit))
                         ->endkey(array('',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->type_transaction,$vrac->produit, array()))->limit(10)->getView('vrac', 'vracSimilaire');
        
                            
                            
        }
        return ($vrac->mandataire_exist)?
                    $this->startkey(array('M',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->mandataire_identifiant,$vrac->type_transaction,$vrac->produit,$vrac->volume_propose))
                         ->endkey(array('M',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->mandataire_identifiant,$vrac->type_transaction,$vrac->produit,$vrac->volume_propose, array()))->limit(10)->getView('vrac', 'vracSimilaire')
                  : $this->startkey(array('',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->type_transaction,$vrac->produit,$vrac->volume_propose))
                         ->endkey(array('',$vrac->vendeur_identifiant,$vrac->acheteur_identifiant,$vrac->type_transaction,$vrac->produit,$vrac->volume_propose, array()))->limit(10)->getView('vrac', 'vracSimilaire');

    }
    
    public function retrieveSimilaryContractsWithProdTypeVol($params) {
        if((empty($params['vendeur']))
          || (empty($params['acheteur']))
          || (empty($params['type']))) {

        	return false;
    	}

        if(empty($params['produit']) && !empty($params['volume'])) {

        	return false;
        }
        
        if(empty($params['volume']) && empty($params['produit'])) {
            
            return $this->startkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['type']))
               ->endkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['type'], array()))->limit(10)->getView('vrac', 'vracSimilaire');
        }
        
        if(empty($params['volume'])) {

        	return $this->startkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['type'],$params['produit']))
               ->endkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['type'],$params['produit'], array()))->limit(10)->getView('vrac', 'vracSimilaire');
        }

        $volumeBas = ((float) $params['volume'])*0.95;
        $volumeHaut = ((float) $params['volume'])*1.05;
        
        return $this->startkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['type'],$params['produit'],$volumeBas))
               ->endkey(array($params['vendeur'],$params['acheteur'],$params['mandataire'],$params['type'],$params['produit'],$volumeHaut, array()))->limit(10)->getView('vrac', 'vracSimilaire');            
    }
    
    public function filterSimilaryContracts($vrac,$similaryContracts) {
        foreach ($similaryContracts->rows as $key => $value) {
            if($value->id === $vrac->_id){
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
	throw new sfException('Le hash du produit ne correpond pas au hash initial ('.$vrac->produit.'<->'.$hash.')');
      return $vrac;
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Vrac 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        
        return parent::retrieveDocumentById('VRAC-'.$id, $hydrate);
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
      return sprintf('%s%s%s',substr($id,0,8), $separation, substr($id,8,  strlen($id)-1));
    }
    
    public function getLibelleContratNum($id)
    {
       // if(strlen($id)!=13) throw new Exception(sprintf ('Le numéro de contrat %s ne possède pas un bon format.',$id));
        $annee = substr($id, 0,4);
        $mois = substr($id, 4,2);
        $jour = substr($id, 6,2);
        $num = substr($id, 8);
        return $jour.'/'.$mois.'/'.$annee.' n° '.$num;
    }
    
    public function retreiveByStatutsTypes($statuts,$types) {
        return VracStatutAndTypeView::getInstance()->findContatsByStatutsAndTypes($statuts,$types);
    }
    
    public function retreiveByStatutsTypesAndDate($statuts,$types,$date) {
        return VracStatutAndTypeView::getInstance()->findContatsByStatutsAndTypesAndDate($statuts,$types,$date);
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
    
}
