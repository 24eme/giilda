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

    public function createMouvements($coefficient) {
        $mouvements = array();

        foreach($this as $detail) {
            $mouvements[] = $this->createMouvement($detail, $coefficient);
        }

        return $mouvements;
    }

    public function createMouvement($detail, $coefficient) {
        $volume = $detail->volume;

        if ($this->getDocument()->hasVersion() && !$this->getDocument()->isModifiedMother($detail, 'volume')) {

          return false;
        }

        if($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($detail->getHash())) {
          $volume = $volume - $this->getDocument()->motherGet($detail->getHash())->volume;
        }

        if(!$volume > 0) {

          return false;
        }

        $mouvement = DRMMouvement::freeInstance($this->getDocument());
        $mouvement->produit_hash = $this->getDetail()->getHash();
        $mouvement->produit_libelle = $this->getDetail()->getLibelle("%g% %a% %m% %l% %co% %ce% %la%");
        $mouvement->type_hash = $this->getNoeud()->getKey().'/'.$this->getTotalHash();
        $mouvement->type_libelle = $this->getDetail()->getConfig()->get($mouvement->type_hash)->getLibelle();
        $mouvement->volume = $coefficient * $volume;
        $mouvement->detail_identifiant = $detail->identifiant;
        $mouvement->detail_libelle = $detail->getIdentifiantLibelle();
        $mouvement->facture = 0;
        $mouvement->facturable = 0;
        $mouvement->version = 'V 1';
        $mouvement->date_version = date('c');

        return $mouvement;
    }

}