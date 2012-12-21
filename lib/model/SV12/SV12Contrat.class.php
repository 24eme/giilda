<?php
/**
 * Model for SV12Contrat
 *
 */

class SV12Contrat extends BaseSV12Contrat {
    protected $vrac = null;

    public function getMouvementVendeur() {
        $mouvement = $this->getMouvement();
        if (!$mouvement) {

            return null;
        }
        $mouvement->vrac_destinataire = $this->getDocument()->declarant->nom;
        if ($this->getVrac()) {
        	$mouvement->cvo = $this->getDroitCVO()->taux * $this->getVrac()->cvo_repartition * 0.01;
	} else {
        	$mouvement->cvo = $this->getDroitCVO()->taux * 0.5;
        }

        return $mouvement;
    }


    public function getMouvementAcheteur() {
        $mouvement = $this->getMouvement();
        if (!$mouvement) {
            
            return null;
        }

        $mouvement->vrac_destinataire = $this->vendeur_nom;
        if ($this->getVrac()) {
            $mouvement->cvo = $this->getDroitCVO()->taux * $this->getVrac()->cvo_repartition * 0.01;
        } else if ($this->vendeur_identifiant) {
	  $mouvement->cvo = $this->getDroitCVO()->taux * 0.5;	  
	} else {
	  $mouvement->cvo = $this->getDroitCVO()->taux;	  
	}

        return $mouvement;
    }

    protected function getVolumeVersion() {
        if ($this->getDocument()->hasVersion() && !$this->getDocument()->isModifiedMother($this, 'volume')) {

            return 0;
        }

        $volume = $this->volume;

        if($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($this->getHash().'/volume')) {
            $volume = $volume - $this->getDocument()->motherGet($this->getHash().'/volume');
        }

        return $volume;
    }

    protected function getMouvement() {

        $volume = $this->getVolumeVersion();

        if($volume == 0) {
            return null;
        }

        $mouvement = DRMMouvement::freeInstance($this->getDocument());
        $mouvement->produit_hash = $this->produit_hash;
        $mouvement->facture = 0;
        $mouvement->version = $this->getDocument()->version;
        $mouvement->date_version = date('Y-m-d');
        if ($this->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS;  
        } elseif($this->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS;  
        }
        if (!$this->getVrac())
        	$mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_ECART;  
        $mouvement->type_hash = $this->contrat_type;
        $mouvement->type_libelle = $this->contrat_type;;
        $mouvement->volume = -1 * $volume;
	$mouvement->facturable = 1;
        $mouvement->date = $this->getDocument()->getDate();
        $mouvement->vrac_numero = $this->contrat_numero;
        if ($this->getVrac())
        	$mouvement->detail_identifiant = $this->getVracIdentifiant();
        else 
        	$mouvement->detail_identifiant = null;
        $mouvement->detail_libelle = $this->contrat_numero;

        return $mouvement;
    }
    
    public function canBeSoldable() {
        
        return $this->volume > 0; 
    }

    public function enleverVolume() {
        $volume = $this->getVolumeVersion();
        if ($volume == 0) {
            return false;
        }
		if (!$this->getVrac())
			return false;
        $this->getVrac()->enleverVolume($this->getVolumeVersion());
        if ($this->canBeSoldable()) {
            $this->getVrac()->solder();
        }

        return true;
    }

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->vrac = VracClient::getInstance()->find($this->getVracIdentifiant());
        }

        return $this->vrac;
    }

    public function getVracIdentifiant() {

        return 'VRAC-'.$this->contrat_numero;
    }

    public function getDroitCVO() {

        return $this->getProduitObject()->getDroitCVO($this->getDocument()->getDate());
    }

    public function getProduitObject() 
    {

        return ConfigurationClient::getCurrent()->get($this->produit_hash);
    }

    function updateNoContrat($produit, $infoviti = array('contrat_type' => null, 'vendeur_identifiant' => null, 'vendeur_nom' => null))
    {
      if ($this->volume)
	return ;
      $this->contrat_numero = null;
      $this->contrat_type = $infoviti['contrat_type'];
      $this->produit_libelle = $produit->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
      $this->produit_hash = $produit->getHash();
      $this->vendeur_identifiant = $infoviti['vendeur_identifiant'];
      $this->vendeur_nom = $infoviti['vendeur_nom'];
      $this->volume_prop = null;
      $this->volume = null;
    }

}