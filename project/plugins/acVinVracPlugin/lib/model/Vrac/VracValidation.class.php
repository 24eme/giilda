<?php

class VracValidation extends DocumentValidation {

    protected $teledeclaration = false;

    public function __construct($document, $isTeledeclarationMode = false, $options = null) {
        $this->teledeclaration = $isTeledeclarationMode;
        parent::__construct($document, $options);
    }

    public function configure() {
        if ($this->teledeclaration) {
            $this->addControle('vigilance', 'soussigne_vendeur_nonactif', "Le compte du vendeur n'est pas actif");
            $this->addControle('vigilance', 'soussigne_acheteur_nonactif', "Le compte de l'acheteur n'est pas actif");
            $this->addControle('vigilance', 'soussigne_courtier_nonactif', "Le compte du courtier n'est pas actif");            
            $this->addControle('erreur', 'date_enlevement_inf_minimale', "La date d'enlèvement est inférieure à la date minimale d'enlèvement du produit");
            
            $this->addControle('erreur', 'soussigne_vendeur_absence_mail', "Aucun email renseigné pour");
            $this->addControle('erreur', 'soussigne_acheteur_absence_mail', "Aucun email renseigné pour");
            $this->addControle('erreur', 'soussigne_courtier_absence_mail', "Aucun email renseigné pour");
            
            $this->addControle('erreur', 'date_enlevement_sup_maximale', "La date d'enlèvement est supérieure à la date maximale d'enlèvement du produit");
        } else {
            $this->addControle('erreur', 'hors_interloire_raisins_mouts', "Le négociant ne fait pas parti d'Interloire et le contrat est un contrat de raisins/moûts");
            $this->addControle('erreur', 'mauvaise_hash_produit', "Le produit n'est pas reconnu dans la campagne sélectionnée");
            $this->addControle('vigilance', 'stock_commercialisable_negatif', 'Le stock commercialisable est inférieur au stock proposé');
            $this->addControle('vigilance', 'contrats_similaires', 'Risque de doublons');
            $this->addControle('vigilance', 'prix_definitif_expected', "Le prix définitif de contrat n'a pas été saisi");
        }
        $this->addControle('erreur', 'volume_expected', 'Le volume du contrat est manquant');
        $this->addControle('erreur', 'prix_initial_expected', 'Le prix du contrat est manquant');
        $this->addControle('erreur', 'viti_raisins_mouts_type_vins', "Le viticulteur ne peut pas faire de contrats de vins (il possède une exclusivité de raisins/moûts)");
        $this->addControle('vigilance', 'prix_too_low', 'Le prix est trop bas');
    }

    public function controle() {

        if ($this->teledeclaration) {
            $this->checkSoussigneAbsenceMail();
            $this->checkSoussigneCompteNonActive();
         //   $this->checkDateEnlevement();
        } else {

            if (!$this->document->getConfig()->exist($this->document->produit)) {
                $this->addPoint('erreur', 'mauvaise_hash_produit', 'le changer', $this->generateUrl('vrac_marche', $this->document));
            }

            if ($this->document->isRaisinMoutNegoHorsIL()) {
                $this->addPoint('erreur', 'hors_interloire_raisins_mouts', 'changer', $this->generateUrl('vrac_soussigne', $this->document));
            }

            if ($this->document->isVin() && $this->document->volume_propose > $this->document->getStockCommercialisable()) {
                $this->addPoint('vigilance', 'stock_commercialisable_negatif', 'modifier le volume', $this->generateUrl('vrac_marche', $this->document));
            }

            $nbsimilaires = count(array_keys(VracClient::getInstance()->retrieveSimilaryContracts($this->document)));
            if ($nbsimilaires) {
                $this->addPoint('vigilance', 'contrats_similaires', 'Il y a ' . $nbsimilaires . ' contrat(s) similaire(s)');
            }

            if ($this->document->hasPrixVariable() && !$this->document->hasPrixDefinitif()) {
                $this->addPoint('vigilance', 'prix_definitif_expected', 'saisir le prix définitif', $this->generateUrl('vrac_marche', $this->document));
            }
        }

        if (!$this->document->volume_propose) {
            $this->addPoint('erreur', 'volume_expected', 'saisir un volume', $this->generateUrl('vrac_marche', $this->document));
        }
        if ($this->document->isVitiRaisinsMoutsTypeVins()) {
            $this->addPoint('erreur', 'viti_raisins_mouts_type_vins', 'modifier vendeur', $this->generateUrl('etablissement_visualisation', EtablissementClient::getInstance()->find($this->document->vendeur_identifiant)));
        }
        if (is_null($this->document->prix_initial_unitaire)) {
            $this->addPoint('erreur', 'prix_initial_expected', 'saisir un prix', $this->generateUrl('vrac_marche', $this->document));
        }

        if ($this->document->prix_initial_unitaire_hl < 10) {
            $this->addPoint('vigilance', 'prix_too_low', 'Le prix est inférieur à 10€/hl ['. $this->document->prix_initial_unitaire_hl .'€/hl]', $this->generateUrl('vrac_marche', $this->document));
        }
    }

    private function checkSoussigneAbsenceMail() {
        $vendeurEtb = EtablissementClient::getInstance()->findByIdentifiant($this->document->vendeur_identifiant);

        if (!$vendeurEtb->getEmailTeledeclaration()) {
            $this->addPoint('erreur', 'soussigne_vendeur_absence_mail', $vendeurEtb->nom );
        }

        $acheteurEtb = EtablissementClient::getInstance()->findByIdentifiant($this->document->acheteur_identifiant);
        if (!$acheteurEtb->getEmailTeledeclaration()) {
            $this->addPoint('erreur', 'soussigne_acheteur_absence_mail', $acheteurEtb->_id);
        }
        if ($this->document->mandataire_exist) {
            $courtierEtb = EtablissementClient::getInstance()->findByIdentifiant($this->document->mandataire_identifiant);
            if (!$courtierEtb->getEmailTeledeclaration()) {
                $this->addPoint('erreur', 'soussigne_courtier_absence_mail', $courtierEtb->nom);
            }
        }
    }

    private function checkSoussigneCompteNonActive() {
        $vendeurEtb = EtablissementClient::getInstance()->findByIdentifiant($this->document->vendeur_identifiant);
        $vendeurCompte = CompteClient::getInstance()->find($vendeurEtb->getSociete()->getCompteSociete());
        if (!$vendeurCompte->isTeledeclarationActive()) {
            $this->addPoint('vigilance', 'soussigne_vendeur_nonactif', '');
        }

        $acheteurEtb = EtablissementClient::getInstance()->findByIdentifiant($this->document->acheteur_identifiant);
        $acheteurCompte = CompteClient::getInstance()->find($acheteurEtb->getSociete()->getCompteSociete());
        if (!$acheteurCompte->isTeledeclarationActive()) {
            $this->addPoint('vigilance', 'soussigne_acheteur_nonactif', '');
        }
        if ($this->document->mandataire_exist) {
            $courtierEtb = EtablissementClient::getInstance()->findByIdentifiant($this->document->mandataire_identifiant);
            $courtierCompte = CompteClient::getInstance()->find($courtierEtb->getSociete()->getCompteSociete());

            if (!$courtierCompte->isTeledeclarationActive()) {
                $this->addPoint('vigilance', 'soussigne_courtier_nonactif', '');
            }
        }
    }

    private function checkDateEnlevement() {
        $produits = $this->document->getConfig()->getProduits();
        $produit = null;
        foreach ($produits as $hash => $produitConf) {
            if($this->document->produit == $hash){
                $produit = $produitConf;
                break;
            }
        }
        $millesime = null;
        if(!($millesime = $this->document->millesime)){
            return;
        }
        if(!$produit->getDateCirulation($millesime)){
            return;
        }
        $date_debut = $produit->getDateCirulation($millesime)->date_debut;
        $date_fin = $produit->getDateCirulation($millesime)->date_fin;
        if($this->document->enlevement_date && $date_debut){
            if(str_replace('-', '', $this->document->enlevement_date) < str_replace('-', '', $date_debut)){
                $date_debutArr = explode('-', $date_debut);
                $date_debutFR = $date_debutArr[2].'/'.$date_debutArr[1].'/'.$date_debutArr[0];
                $this->addPoint('erreur', 'date_enlevement_inf_minimale', ' ('.$date_debutFR.')');
            }
        }
        if($this->document->enlevement_date && $date_fin){
            if(str_replace('-', '', $this->document->enlevement_date) > str_replace('-', '', $date_fin)){
                $date_finArr = explode('-', $date_fin);
                $date_finFR = $date_finArr[2].'/'.$date_finArr[1].'/'.$date_finArr[0];
                $this->addPoint('erreur', 'date_enlevement_sup_maximale', ' ('.$date_finFR.')');
            }
        }
    }
}
