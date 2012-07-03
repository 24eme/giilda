<?php
/**
 * Model for Vrac
 *
 */

class Vrac extends BaseVrac {
    
    public function constructId() {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);

        if(!$this->date_signature) {
            $this->date_signature = date('d/m/Y');
        }
        
        if(!$this->date_stats) {
            $this->date_stats = date('d/m/Y');
        }
    }
    
    public function setBouteillesContenanceLibelle($c) {
        $this->_set('bouteilles_contenance_libelle', $c);
        if ($c) {
            $this->setBouteillesContenanceVolume(VracClient::$contenance[$c]);
        }
    }
    
    public function update($params = array()) {
        
         $this->prix_total = null;

        switch ($this->type_transaction)
        {
            case 'raisins' :
            {
                $this->prix_total = $this->raisin_quantite * $this->prix_unitaire;
                $this->bouteilles_contenance_libelle = '';
                $this->bouteilles_contenance_volume = null;
                $this->volume_propose = $this->getDensite() * $this->raisin_quantite;
                break;
            }
            case 'vin_bouteille' :
            {
                $this->prix_total = $this->bouteilles_quantite * $this->prix_unitaire;
                $this->volume_propose = $this->bouteilles_quantite * $this->bouteilles_contenance_volume;
                break;
            }
            
            case 'mouts' :
            case 'vin_vrac' :
            {
                $this->prix_total = $this->jus_quantite * $this->prix_unitaire;              
                $this->bouteilles_contenance_libelle = '';
                $this->bouteilles_contenance_volume = null;
                $this->volume_propose = $this->jus_quantite;
            }              
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

    private function getDensite() 
    {
        return 1.3;
    }   
}