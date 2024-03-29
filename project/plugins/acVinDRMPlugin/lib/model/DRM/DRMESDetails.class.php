<?php

/**
 * Model for DRMESDetails
 *
 */
class DRMESDetails extends BaseDRMESDetails {

    public function update($params = array()) {
        parent::update($params);
    }

    public function cleanEmpty() {
        $itemsToDelete = array();
        foreach($this as $item) {
            if($item->volume) {
                continue;
            }

            $itemsToDelete[] = $item->getKey();
        }

        foreach ($itemsToDelete as $key) {
            $this->remove($key);
        }
    }

    public function getNoeud() {

        return $this->getParent();
    }

    public function getTotalHash() {
        return str_replace('_details', '', $this->getKey());
    }

    public function getProduitDetail() {

        return $this->getParent()->getParent();
    }

    public function init($params = array()) {
        parent::init($params);

        $this->getParent()->remove($this->getKey());
        $this->getParent()->add($this->getKey());
    }

    public function addDetail($detail) {
         return $this->add($detail->getKey(),$detail);
    }

    public function createMouvements($template_mouvement) {
        $mouvements = array();
        // Check les éventuels suppressions
        if ($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($this->getHash())) {
            $mother_this = $this->getDocument()->motherGet($this->getHash());
            foreach ($mother_this as $key => $mother_detail) {
                if (!$this->exist($key)) {
                    $detail = $this->add($key, $mother_detail);
                    $detail->volume = 0;
                    $this->pushMouvement($mouvements, $template_mouvement, $detail);
                    $this->remove($key);
                }
            }
        }

        foreach ($this as $detail) {
            $this->pushMouvement($mouvements, $template_mouvement, $detail);
        }

        return $mouvements;
    }

    public function pushMouvement(&$mouvements, $template_mouvement, $detail) {
        $config = $this->getConfig();

        $mouvement = $this->createMouvement(clone $template_mouvement, $detail);
        if (!$mouvement) {
            return;
        }
        $md5 = $mouvement->getMD5Key();

        $mouvement_vrac_destinataire = $this->createMouvementVracDestinataire(clone $mouvement, $detail);
        $mouvement_vrac_intermediaire = $this->createMouvementVracIntermediaire(clone $mouvement, $detail);

        if(!$this->getDocument()->isFacturable() && $config->isFacturableInverseNegociant() && $mouvement->cvo > 0) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_NEGOCIANT_RECOLTE;
        }

        $mouvements[$this->getDocument()->getIdentifiant()][$md5] = $mouvement;

        if ($mouvement_vrac_destinataire) {
            $mouvements[$detail->getVrac()->acheteur_identifiant][$mouvement_vrac_destinataire->getMD5Key()] = $mouvement_vrac_destinataire;
            $mouvements[$this->getDocument()->getIdentifiant()][$md5]['region_destinataire'] = $mouvement_vrac_destinataire->region;
        }

        if ($mouvement_vrac_intermediaire) {
            $mouvements[$detail->getVrac()->representant_identifiant][$mouvement_vrac_intermediaire->getMD5Key()] = $mouvement_vrac_intermediaire;
        }
    }

    public function getConfig() {

        return $this->getProduitDetail()->getConfig()->get($this->getNoeud()->getKey() . '/' . $this->getTotalHash());
    }

    public function createMouvement($mouvement, $detail) {
        $volume = $detail->volume;
        if ($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($detail->getHash())) {
            $volume = round($volume - $this->getDocument()->motherGet($detail->getHash())->volume, FloatHelper::getInstance()->getMaxDecimalAuthorized());
        }

        $config = $this->getConfig();
        $volume = $config->mouvement_coefficient * $volume;

        if ($volume == 0) {

            return null;
        }

        $mouvement->detail_identifiant = $detail->identifiant;
        $mouvement->detail_libelle = $detail->getIdentifiantLibelle();
        $mouvement->type_libelle = $config->getLibelle();
        $mouvement->type_hash .= $this->getKey();
        $mouvement->volume = $volume;

        if ($config->isVrac() && !$detail->isContratExterne() && !$detail->isSansContrat()) {
            $vrac = $detail->getVrac();
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
            $mouvement->vrac_numero = $vrac->numero_contrat;
            if($vrac instanceof stdClass){
                $mouvement->vrac_destinataire = $vrac->acheteur->raison_sociale;
                $mouvement->cvo = 0;
            }else{
                $mouvement->vrac_destinataire = $vrac->acheteur->nom;
                $mouvement->cvo = $this->getProduitDetail()->getCVOTaux() * $vrac->getRepartitionCVOCoef($vrac->vendeur_identifiant, $detail->getDocument()->getDate());
            }
        }

        if ($config->isVrac() && $detail->isSansContrat()) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
            $mouvement->vrac_numero = null;
            $mouvement->vrac_destinataire = null;
        }

        if ($config->isVrac() && $detail->isContratExterne()) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS_EXTERNE;
            $mouvement->vrac_numero = null;
            $mouvement->vrac_destinataire = null;
        }

        $mouvement->date = ($detail->exist('date_enlevement'))? $detail->date_enlevement : $this->getDocument()->getDate();

        return $mouvement;
    }

    public function createMouvementVracDestinataire($mouvement, $detail) {
        $config = $this->getConfig();

        if (!$config->isVrac() || $detail->isSansContrat() || !$detail->getVrac() instanceof Vrac) {

            return null;
        }

        if(!$detail->getVrac()) {

            return null;
        }

        $mouvement->vrac_destinataire = $detail->getVrac()->vendeur->nom;
        $mouvement->region = $detail->getVrac()->getAcheteurObject()->region;
        $mouvement->cvo = $this->getProduitDetail()->getCVOTaux() * $detail->getVrac()->getRepartitionCVOCoef($detail->getVrac()->acheteur_identifiant, $detail->getDocument()->getDate());
        if ($mouvement->cvo > 0 && $mouvement->volume) {
            $mouvement->facturable = 1;
        }

        if(!$this->getDocument()->isFacturable()){
            $mouvement->facturable = 0;
        }

        if(!$this->getDocument()->isFacturable() && $config->isFacturableInverseNegociant() && $mouvement->cvo > 0) {
            $mouvement->facturable = 1;
            $mouvement->remove('coefficient_facturation', 1);
        }
        return $mouvement;
    }

    public function createMouvementVracIntermediaire($mouvement, $detail) {
        $config = $this->getConfig();

        if (!$config->isVrac() || $detail->isSansContrat() || !$detail->getVrac() instanceof Vrac) {
            return null;
        }

        if(!$detail->getVrac()) {

            return null;
        }

        if ($detail->getVrac()->representant_identifiant == $detail->getVrac()->vendeur_identifiant) {
            return null;
        }

        $mouvement->vrac_destinataire = $detail->getVrac()->acheteur->nom;
        $mouvement->region = $detail->getVrac()->representant->region;
        $mouvement->cvo = $this->getProduitDetail()->getCVOTaux() * $detail->getVrac()->getRepartitionCVOCoef($detail->getVrac()->representant_identifiant, $detail->getDocument()->getDate());
        if ($mouvement->cvo > 0 && $mouvement->volume) {
            $mouvement->facturable = 1;
        }
        if(!$this->getDocument()->isFacturable()){
            $mouvement->facturable = 0;
        }
        if(!$this->getDocument()->isFacturable() && $config->isFacturableInverseNegociant() && $mouvement->cvo > 0) {
            $mouvement->facturable = 1;
            $mouvement->remove('coefficient_facturation', 1);
        }

        return $mouvement;
    }


}
