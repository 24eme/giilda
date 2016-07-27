<?php

/**
 * Model for DRMCepage
 *
 */
class DRMCepage extends BaseDRMCepage {

    public function getChildrenNode() {

        return $this->details;
    }

    public function getCouleur() {

        return $this->getParentNode();
    }

    public function getProduits() {

        return array($this->getHash() => $this);
    }

    public function getProduitsDetails($teledeclarationMode = false) {
        $details = array();
        foreach ($this->getChildrenNode() as $key => $item) {
	    if ($teledeclarationMode || $this->getConfig()->isCVOActif($this->getDocument()->getDate())) {
	            $details[$item->getHash()] = $item;
	    }
        }

        return $details;
    }

    public function getInao() {
	$inao = $this->_get('inao');
        if ($inao != $this->getConfig()->getInao()) {
		$inao = $this->getConfig()->getInao();
		$this->setInao($inao);
	}
	return $inao;
    }
	
    public function hasProduitDetailsWithStockNegatif() {
        foreach ($this->getProduitsDetails() as $detail) {
            if ($detail->total < 0) {
                return true;
            }
        }

        return false;
    }

    public function getLieuxArray() {

        throw new sfException('this function need to call before lieu tree');
    }

    public function cleanNoeuds() {
        if (count($this->details) == 0) {
            return $this;
        }

        return null;
    }
    
    public function hasMovements(){
        return !$this->exist('no_movements') || !$this->no_movements;
    }
}
