<?php
/**
 * Model for Vrac
 *
 */

class Vrac extends BaseVrac {
    
    public function constructId() {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);
        $this->set('date_signature', date('d/m/Y'));
        $this->set('date_stats', date('d/m/Y'));
    }
    
    public function update($params = array()) {
        
        switch ($this->type_transaction)
        {
            case 'raisins' :
            {
                $this->prix_total = $this->raisin_quantite * $this->prix_unitaire;
                break;
            }
            
            case 'mouts' :
            {
                $this->prix_total = $this->jus_quantite * $this->prix_unitaire;                
                break;
            } 
            
            case 'vin_vrac' :
            {
                $this->prix_total = $this->jus_quantite * $this->prix_unitaire;              
                break;
            }  
            
            case 'vin_bouteille' :
            {
                $this->prix_total = $this->bouteilles_quantite * $this->prix_unitaire;
                break;
            }
            default :
                $this->prix_total = null;
        }
        
    }

    public function setInformations() 
    {        
        $this->setAcheteurInformations();
        $this->setVendeurInformations();
        if($this->mandataire_identifiant!=null && $this->mandataire_exist)
        {
            $this->setMandataireInformations();
            
        }
    }

    private function setAcheteurInformations() 
    {
       $acheteurObj = $this->getAcheteurObject();
       $this->acheteur->nom = $acheteurObj->nom;
       $this->acheteur->cvi = $acheteurObj->cvi;
       $this->acheteur->commune = $acheteurObj->commune;
       $this->acheteur->code_postal = $acheteurObj->code_postal;
    }
    
    private function setMandataireInformations() 
    {
       $mandataireObj = $this->getMandataireObject();
       $this->mandataire->nom = $mandataireObj->nom;
       //TODO : surement à changer
       $this->mandataire->carte_pro = $mandataireObj->identifiant;
       $this->mandataire->adresse = $mandataireObj->commune.'  '.$mandataireObj->code_postal;
    }
    
    private function setVendeurInformations() 
    {
       $vendeurObj = $this->getVendeurObject();
       $this->vendeur->nom = $vendeurObj->nom;
       $this->vendeur->cvi = $vendeurObj->cvi;
       $this->vendeur->commune = $vendeurObj->commune;
       $this->vendeur->code_postal = $vendeurObj->code_postal;       
    }

    public function getProduitObject() 
    {
      return ConfigurationClient::getCurrent()->get($this->produit);
    }

    public function getVendeurObject() 
    {
        return EtablissementClient::getInstance()->find($this->vendeur_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function getAcheteurObject() 
    {
        return EtablissementClient::getInstance()->find($this->acheteur_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function getMandataireObject() 
    {
        return EtablissementClient::getInstance()->find($this->mandataire_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function getSoussigneObjectById($soussigneId) 
    {
        return EtablissementClient::getInstance()->find($soussigneId,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function showUnite()
    {
        if($type = $this->type_transaction)
        {
            switch ($type)
            {
                case 'raisins': return 'kg';
                case 'mouts': return 'hl';
                case 'vin_vrac': return 'hl';                    
                case 'vin_bouteille': return 'btle';
            }
        }    
        return '';
    }
    
    public function showRecapVolume()
    {
        if($type = $this->type_transaction)
        {
            switch ($type)
            {
                case 'raisins': return $this->raisin_quantite.' kg (raisins)';
                case 'mouts': return $this->jus_quantite.' hl (moûts)';
                case 'vin_vrac': return $this->jus_quantite.' hl (vin vrac)';                   
                case 'vin_bouteille': 
                    return $this->bouteilles_quantite.
                        ' bouteilles, soit '.$this->bouteilles_quantite*($this->bouteilles_contenance/10000).' hl';
            }
        }    
        return '';
    }
    
    public function showRecapPrixUnitaire()
    {
        if($type = $this->type_transaction)
        {
            switch ($type)
            {
                case 'raisins': return $this->prix_unitaire.' €/kg';
                case 'mouts': return $this->prix_unitaire.' €/hl';
                case 'vin_vrac': return $this->prix_unitaire.' €/hl';                   
                case 'vin_bouteille': 
                    return $this->prix_unitaire.' €/btle, soit '.
                        $this->prix_total/($this->bouteilles_quantite*($this->bouteilles_contenance/10000)).' €/hl';
            }
        }    
        return '';
    }
    
}