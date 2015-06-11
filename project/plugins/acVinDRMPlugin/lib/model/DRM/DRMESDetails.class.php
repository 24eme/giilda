<?php
/**
 * Model for DRMESDetails
 *
 */

class DRMESDetails extends BaseDRMESDetails {

  public function update($params = array()) {
    parent::update($params);
    /*if (count($this) == 1 && !$this[0]->identifiant) {
      $p = $this->getParent();
      $k = $this->getKey();
      $this->delete();
      $p->add($k);
    }*/
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

    public function addDetail($identifiant = null, $volume = null, $date_enlevement = null, $numero_document = null) {
        $detail = $this->add($identifiant);

        $detail->identifiant = $identifiant;
        
        if ($volume && is_null($detail->volume)) {
           $detail->volume = $volume; 
        } elseif($volume) {
          $detail->volume += $volume; 
        }

        if($date_enlevement) {
            $detail->date_enlevement = $date_enlevement;
        }
        
        if($numero_document) {
            $detail->numero_document = $numero_document;
               $documents_administration = $this->getDocument()->getOrAdd('documents_administration');
            if($detail instanceof DRMESDetailExport){
                if(!$documents_administration->exist('dae_debut') 
                        || !$documents_administration->exist('dae_fin')
                        || !$documents_administration->dae_debut
                        || !$documents_administration->dae_fin){
                    $documents_administration->dae_debut = $numero_document;
                    $documents_administration->dae_fin = $numero_document;
                }else{
                    if(strcmp($numero_document, $documents_administration->dae_debut) < 0){
                        $this->getDocument()->documents_administration->dae_debut = $numero_document;
                    }
                    if(strcmp($numero_document, $documents_administration->dae_fin) > 0){
                        $documents_administration->dae_fin = $numero_document;
                    }
                }
                
            }            
            if($detail instanceof DRMESDetailVrac){
                if(!$documents_administration->exist('dsa_daa_debut') 
                        || !$documents_administration->exist('dsa_daa_fin')
                        || !$documents_administration->dsa_daa_debut
                        || !$documents_administration->dsa_daa_fin){
                    $documents_administration->dsa_daa_debut = $numero_document;
                    $documents_administration->dsa_daa_fin = $numero_document;
                }else{
                    if(strcmp($numero_document, $documents_administration->dsa_daa_debut) < 0){
                        $this->getDocument()->documents_administration->dae_debut = $numero_document;
                    }
                    if(strcmp($numero_document, $documents_administration->dsa_daa_fin) > 0){
                        $documents_administration->dsa_daa_fin = $numero_document;
                    }
                }
                
            }
        }

        return $detail;
    }

    public function createMouvements($template_mouvement) {
        $mouvements = array();

        // Check les éventuels suppressions
        if($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($this->getHash())) {
            $mother_this = $this->getDocument()->motherGet($this->getHash());
            foreach($mother_this as $key => $mother_detail) {
                if(!$this->exist($key)) {
                    $detail = $this->add($key, $mother_detail);
                    $detail->volume = 0;
                    $this->pushMouvement($mouvements, $template_mouvement, $detail);
                    $this->remove($key);
                }
            }
        }

        foreach($this as $detail) {
	        $this->pushMouvement($mouvements, $template_mouvement, $detail);
        }

        return $mouvements;
    }

    public function pushMouvement(&$mouvements, $template_mouvement, $detail) {
        $mouvement = $this->createMouvement(clone $template_mouvement, $detail);
        if(!$mouvement){
            return;
        }
        $mouvements[$this->getDocument()->getIdentifiant()][$mouvement->getMD5Key()] = $mouvement;

        $mouvement_vrac_destinataire = $this->createMouvementVracDestinataire(clone $mouvement, $detail);
        if (!$mouvement_vrac_destinataire) {
            return;
        }
        $mouvements[$detail->getVrac()->acheteur_identifiant][$mouvement->getMD5Key()] = $mouvement_vrac_destinataire;
    }

    public function createMouvement($mouvement, $detail) {
        $volume = $detail->volume;

        if($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($detail->getHash())) {
          $volume = $volume - $this->getDocument()->motherGet($detail->getHash())->volume;
        }

	    $config = $this->getProduitDetail()->getConfig()->get($this->getNoeud()->getKey().'/'.$this->getTotalHash());
        $volume = $config->mouvement_coefficient * $volume;

        if($volume == 0) {

          return null;
        }

        $mouvement->detail_identifiant = $detail->identifiant;
        $mouvement->detail_libelle = $detail->getIdentifiantLibelle();
	    $mouvement->type_libelle = $config->getLibelle();
        $mouvement->type_hash .= $this->getKey();
        $mouvement->volume = $volume;

        if($config->isVrac()) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
            $mouvement->vrac_numero = $detail->getVrac()->numero_contrat;
            $mouvement->vrac_destinataire = $detail->getVrac()->acheteur->nom;
            $mouvement->cvo = $this->getProduitDetail()->getCVOTaux() * $detail->getVrac()->getRepartitionCVOCoef($detail->getVrac()->vendeur_identifiant);
        }

        $mouvement->date = $detail->date_enlevement;

        return $mouvement;
    }

    public function createMouvementVracDestinataire($mouvement, $detail) {
        $config = $this->getProduitDetail()->getConfig()->get($this->getNoeud()->getKey().'/'.$this->getTotalHash());

        if (!$config->isVrac()) {

            return null;
        }

        $mouvement->vrac_destinataire = $detail->getVrac()->vendeur->nom;
	    $mouvement->region = $detail->getVrac()->getAcheteurObject()->region;
        $mouvement->cvo = $this->getProduitDetail()->getCVOTaux() * $detail->getVrac()->getRepartitionCVOCoef($detail->getVrac()->acheteur_identifiant);
        if($mouvement->cvo && $mouvement->volume) {
            $mouvement->facturable = 1;
        }
        return $mouvement;
    }

}

