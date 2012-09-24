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
        $mouvement->cvo = $this->getDroitCVO()->taux * $this->getVrac()->cvo_repartition * 0.01;

        return $mouvement;
    }


    public function getMouvementAcheteur() {
        $mouvement = $this->getMouvement();
        if (!$mouvement) {
            
            return null;
        }

        $mouvement->vrac_destinataire = $this->vendeur_nom;
        $mouvement->cvo = 0;
        if ($this->getVrac()->cvo_repartition = 50) {
            $mouvement->cvo = $this->getDroitCVO()->taux * 0.5;
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
        $mouvement->produit_libelle = $this->getProduitObject($this->produit_hash)->getLibelleFormat(array(), "%a% %m% %l% %co% %ce% %la%");
        $mouvement->facture = 0;
        $mouvement->version = $this->getDocument()->version;
        $mouvement->date_version = date('Y-m-d');
        if ($this->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS;  
        } elseif($this->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_MOUTS;  
        }
        
        $mouvement->type_hash = $this->contrat_type;
        $mouvement->type_libelle = $this->contrat_type;;
        $mouvement->volume = -1 * $volume;
        $mouvement->date = $this->getDocument()->getDate();
        $mouvement->vrac_numero = $this->contrat_numero;
        $mouvement->detail_identifiant = $this->getVracIdentifiant();
        $mouvement->detail_libelle = $this->contrat_numero;
        $mouvement->facturable = 1;

        return $mouvement;
    }
    
    public function canBeSoldable() {
        
        return $this->volume > 0; 
    }

    public function enleverVolume() {
        $this->getVrac()->enleverVolume($this->getVolumeVersion());
    }

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->vrac = VracClient::getInstance()->find($this->getVracIdentifiant());
        }

        return $this->vrac;
    }

    public function getHTMLId() {

        return 'contrat_'.$this->contrat_numero;
    }

    public function getWords() {
       return array_merge(
            Search::getWords($this->produit_libelle),
            Search::getWords($this->vendeur_nom),
            Search::getWords($this->vendeur_identifiant),
            Search::getWords($this->contrat_numero),
            Search::getWords($this->contrat_type)
        ); 
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

}