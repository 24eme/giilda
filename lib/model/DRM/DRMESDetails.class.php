<?php
/**
 * Model for DRMESDetails
 *
 */

class DRMESDetails extends BaseDRMESDetails {

    public function getNoeud() {

        return $this->getParent();
    }

    public function getTotalHash() {

        return str_replace('_details', '', $this->getKey());
    }

    public function getDetail() {

        return $this->getParent()->getParent();
    }

    public function init($params = array()) {
        parent::init($params);

        $this->getParent()->remove($this->getKey());
        $this->getParent()->add($this->getKey());
    }

    public function createMouvements($template_mouvement) {
        $mouvements = array();

        foreach($this as $detail) {
	        $mouvement = $this->createMouvement(clone $template_mouvement, $detail);
            if(!$mouvement){
                continue;
            }
            $mouvements[$this->getDocument()->getIdentifiant()][$mouvement->getMD5Key()] = $mouvement;

            $mouvement_vrac_destinataire = $this->createMouvementVracDestinataire(clone $mouvement, $detail);

            if (!$mouvement_vrac_destinataire) {
                continue;
            }

            $mouvements[$detail->getVrac()->acheteur_identifiant][$mouvement->getMD5Key()] = $mouvement_vrac_destinataire;
        }
        return $mouvements;
    }  

    public function createMouvement($mouvement, $detail) {
        $volume = $detail->volume;

        if ($this->getDocument()->hasVersion() && !$this->getDocument()->isModifiedMother($detail, 'volume')) {
          return null;
        }

        if($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($detail->getHash())) {
          $volume = $volume - $this->getDocument()->motherGet($detail->getHash())->volume;
        }

	    $config = $this->getDetail()->getConfig()->get($this->getNoeud()->getKey().'/'.$this->getTotalHash());
        $volume = $config->mouvement_coefficient * $volume;

        if($volume == 0) {
          return null;
        }

        $mouvement->detail_identifiant = $detail->identifiant;
        $mouvement->detail_libelle = $detail->getIdentifiantLibelle();
	    $mouvement->type_libelle = $config->getLibelle();
	    $mouvement->facturable = $config->facturable;
        $mouvement->type_hash .= $this->getKey();
        $mouvement->volume = $volume;

        if($config->isVrac()) {
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_PRODUIT_TYPE_VINS;
            $mouvement->vrac_numero = $detail->getVrac()->numero_contrat;
            $mouvement->vrac_destinataire = $detail->getVrac()->acheteur->nom;
            $mouvement->cvo = $mouvement->cvo * $detail->getVrac()->cvo_repartition * 0.01;
        }

        $mouvement->date = $detail->date_enlevement;

        return $mouvement;
    }

    public function createMouvementVracDestinataire($mouvement, $detail) {
        $config = $this->getDetail()->getConfig()->get($this->getNoeud()->getKey().'/'.$this->getTotalHash());

        if (!$config->isVrac()) {

            return null;
        }

        $mouvement->vrac_destinataire = $detail->getVrac()->vendeur->nom;
        
        if($detail->getVrac()->cvo_repartition != 50) {
            $mouvement->cvo = 0;
        }

        return $mouvement;
    }

}

