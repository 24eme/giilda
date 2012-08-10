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
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits[$item->getHash()] = $item;
        }

        return $produits;
    }

  	public function getLieuxArray() {

  		  throw new sfException('this function need to call before lieu tree');
  	}
}