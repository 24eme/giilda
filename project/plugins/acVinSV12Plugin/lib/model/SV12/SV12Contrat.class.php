<?php
/**
 * Model for SV12Contrat
 *
 */

class SV12Contrat extends BaseSV12Contrat {
    protected $vrac = null;

    public function getMouvementVendeur() {
        $mouvement = clone $this->getMouvement();
        $mouvement->vrac_destinataire = $this->getDocument()->declarant->nom;
        $mouvement->cvo = $this->getDroitCVO()->taux * $this->getVrac()->cvo_repartition * 0.01;

        return $mouvement;
    }


    public function getMouvementAcheteur() {
        $mouvement = clone $this->getMouvement();
        $mouvement->vrac_destinataire = $this->vendeur_nom;
        $mouvement->cvo = 0;
        if ($this->getVrac()->cvo_repartition = 50) {
            $mouvement->cvo = $this->getDroitCVO()->taux * 0.5;
        }

        return $mouvement;
    }

    protected function getMouvement() {
        $mouvement = DRMMouvement::freeInstance($this->getDocument());
        $mouvement->produit_hash = $this->produit_hash;
        $mouvement->produit_libelle = $this->produit_libelle;
        $mouvement->facture = 0;
        $mouvement->version = '';
        $mouvement->date_version = date('Y-m-d');
        $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS;
        $mouvement->type_hash = $this->contrat_type;
        $mouvement->type_libelle = $this->contrat_type;;
        $mouvement->volume = -1 * $this->volume;
        $mouvement->date = $this->getDocument()->getDate();
        $mouvement->vrac_numero = $this->contrat_numero;
        $mouvement->detail_identifiant = $this->getVracIdentifiant();
        $mouvement->detail_libelle = $this->contrat_numero;
        $mouvement->facturable = 1;

        return $mouvement;
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

}