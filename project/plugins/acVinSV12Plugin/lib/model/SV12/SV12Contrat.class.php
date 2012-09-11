<?php
/**
 * Model for SV12Contrat
 *
 */

class SV12Contrat extends BaseSV12Contrat {

    public function getMouvement() {
        $mouvement = DRMMouvement::freeInstance($this->getDocument());
        $mouvement->produit_hash = $this->produit_hash;
        $mouvement->produit_libelle = $this->produit_libelle;
        $mouvement->facture = 0;
        // $mouvement->cvo = $this->getDroitCVO()->taux;
        // $mouvement->version = $this->getDocument()->getVersion();
        $mouvement->date_version = date('Y-m-d');
        $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_RAISINS;
        $mouvement->type_hash = $this->contrat_type;
        $mouvement->type_libelle = $this->contrat_type;;
        $mouvement->volume = -1 * $this->volume;
        $mouvement->date = $this->getDocument()->getDate();
        $mouvement->vrac_numero = $this->contrat_numero;
        $mouvement->vrac_destinataire = $this->vendeur_nom;
        $mouvement->detail_identifiant = $this->contrat_numero;
        $mouvement->detail_libelle = $this->contrat_numero;
        $mouvement->facturable = 1;

        return $mouvement;
    }

}