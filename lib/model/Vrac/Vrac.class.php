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

    public function setNumeroContrat($value) {
        $this->_set('numero_contrat', $value);
        $this->campagne = VracClient::getInstance()->buildCampagne($this->numero_contrat);
    }

    public function setProduit($value) {
        $this->_set('produit', $value);
        $this->produit_libelle = $this->getProduitObject()->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce%");
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
        $this->acheteur->commune = $acheteurObj->siege->commune;
        $this->acheteur->code_postal = $acheteurObj->siege->code_postal;
    }
    
    private function setMandataireInformations() 
    {
        $mandataireObj = $this->getMandataireObject();
        $this->mandataire->nom = $mandataireObj->nom;
        //TODO : surement à changer
        $this->mandataire->carte_pro = $mandataireObj->identifiant;
        $this->mandataire->adresse = $mandataireObj->siege->commune.'  '.$mandataireObj->siege->code_postal;
    }
    
    private function setVendeurInformations() 
    {
        $vendeurObj = $this->getVendeurObject();
        $this->vendeur->nom = $vendeurObj->nom;
        $this->vendeur->cvi = $vendeurObj->cvi;
        $this->vendeur->commune = $vendeurObj->siege->commune;
        $this->vendeur->code_postal = $vendeurObj->siege->code_postal;       
    }

    public function setDate($attribut, $d) {
        if (preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $d, $m)) {
	          $d = $m[3].'-'.$m[2].'-'.$m[1];
        }
        return $this->_set($attribut, $d);
    }
    public function getDate($attribut, $format) {
        $d = $this->_get($attribut);
        if (!$format)
	          return $d;
        $date = new DateTime($d);
        return $date->format($format);
    }
    public function setDateSignature($d) {
        return $this->setDate('date_signature', $d);
    }
    public function getDateSignature($format = 'd/m/Y') {
        return $this->getDate('date_signature', $format);
    }
    public function setDateStats($d) {
        return $this->setDate('date_stats', $d);
    }
    public function getDateStats($format = 'd/m/Y') {
        return $this->getDate('date_stats', $format);
    }

    public function getPeriode() {
      $date = $this->getDateSignature('');
      if ($date)
	return $date;
      return date('Y-m-d');
    }

    public function getDroitCVO() {
      return $this->getProduitObject()->getDroitCVO($this->getPeriode());
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

    public function __toString() {

        return sprintf("%s", VracClient::getInstance()->getLibelleFromId($this->get('_id')));
    }
    
    public function enleverVolume($vol)
    {
        $this->volume_enleve += $vol;
        
        if($this->volume_propose <= $this->volume_enleve) { 
          $this->solder();
        }

        if ($this->volume_enleve == 0) {
          $this->desolder();
        }
    }

    public function isSolde() {
        return $this->valide->statut == VracClient::STATUS_CONTRAT_SOLDE;
    }


    public function solder() {
        $this->valide->statut = VracClient::STATUS_CONTRAT_SOLDE;
    }

    public function desolder() {
        $this->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
    }
    
    public function prixDefinitifExist() {
        return ($this->prix_variable) && ($this->part_variable != null);
    }
    
    public function hasVolumeAndPrix() {
        return ((!is_null($this->prix_unitaire)) && (!is_null($this->volume_propose)));
    }

}