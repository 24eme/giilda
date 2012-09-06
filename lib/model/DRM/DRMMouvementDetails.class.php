<?php
/**
 * Model for DRMMouvementDetails
 *
 */

class DRMMouvementDetails extends BaseDRMMouvementDetails {

    public function getNoeud() {

        return $this->getParent();
    }

    public function getTotalHash() {

        return str_replace('_details', '', $this->getKey());
    }

    public function getDetail() {

        return $this->getParent()->getParent();
    }

    public function createMouvements($template_mouvement) {
        $mouvements = array();

        foreach($this as $detail) {
	  $mouvement = $this->createMouvement(clone $template_mouvement, $detail);
            if(!$mouvement){
                continue;
            }
            $mouvements[$mouvement->getMD5Key()] = $mouvement;
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

        if(!$volume > 0) {
          return null;
        }

        $mouvement->detail_identifiant = $detail->identifiant;
        $mouvement->detail_libelle = $detail->getIdentifiantLibelle();
	    $mouvement->type_libelle = $config->getLibelle();
	    $mouvement->facturable = $config->facturable;
        $mouvement->type_hash .= $this->getKey();
        $mouvement->volume = $volume;

        if($config->isVrac()) {
            $mouvement->categorie = 'contrat_vins';
        }

        $mouvement->date = $detail->date_enlevement;


        return $mouvement;
    }

}

