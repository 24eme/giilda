<?php
/**
 * Inheritance tree class _DRMTotal
 *
 */

abstract class _DRMTotal extends acCouchdbDocumentTree {
    
    public function getConfig() {

        return ConfigurationClient::getCurrent()->get($this->getHash());
    }

    public function getParentNode() {

        return $this->getParent()->getParent();
    }

    public function getLibelle($format = "%g% %a% %m% %l% %co% %ce%") {

      return $this->getConfig()->getLibelleFormat(array(), $format);
    }

   	public function getCode($format = "%g%%a%%m%%l%%co%%ce%") {

      return $this->getConfig()->getCodeFormat();
    }

    protected function init($params = array()) {
        parent::init($params);
        $this->total_debut_mois = null;
        $this->total_entrees = null;
        $this->total_sorties = null;
        $this->total = null;
    }
    
	protected function update($params = array()) {
        parent::update($params);
        $this->total_debut_mois = $this->getTotalByKey('total_debut_mois');
        $this->total_entrees = $this->getTotalByKey('total_entrees');
        $this->total_sorties = $this->getTotalByKey('total_sorties');
        $this->total = $this->get('total_debut_mois') + $this->get('total_entrees') - $this->get('total_sorties');
    }
    
    private function getTotalByKey($key) {
    	$sum = 0;
    	foreach ($this->getFields() as $field => $k) {
    		if ($this->fieldIsCollection($field)) {
    			foreach ($this->get($field) as $f => $v) {
    				if ($this->get($field)->fieldIsCollection($f)) {
    					if ($v->exist($key)) {
		    				$sum += $v->get($key);
    					}
    				}
    			}
    		}
    	}
    	return $sum;
    }

    public function hasStockEpuise() {

        return $this->total_debut_mois == 0 && !$this->hasMouvement();
    }


    public function sommeLignes($lines) {
        $sum = 0;
        foreach($this->getChildrenNode() as $item) {
            $sum += $item->sommeLignes($lines);
        }
        
        return $sum;
    }

    public function hasMouvement() {

        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }

    public function hasMouvementCheck() {
        foreach($this->getChildrenNode() as $item) {
            if($item->hasMouvementCheck()) {
                return true;
            }
        }

        return false;
    }

    public function nbComplete() {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbComplete();
        }

        return $nb;
    }

    public function nbToComplete() {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbToComplete();
        }

        return $nb;
    }

    public function isComplete() {
        foreach($this->getChildrenNode() as $item) {
            if(!$item->isComplete()) {
                return false;
            }
        }

        return true;
    }

    public function getPreviousSisterWithMouvementCheck() {
        $item = $this->getPreviousSister();
        $sister = null;

        if ($item) {
            $sister = $item;
        }

        if (!$sister) {
            $item = $this->getParentNode()->getPreviousSisterWithMouvementCheck();
            if ($item) {
               
               $sister = $item->getChildrenNode()->getLast();
            }
        }

        if ($sister && !$sister->hasMouvementCheck()) {

            return $sister->getPreviousSisterWithMouvementCheck();
        }

        return $sister; 
    }

    public function getNextSisterWithMouvementCheck() {
        $item = $this->getNextSister();
        $sister = null;

        if ($item) {
            $sister = $item;
        }

        if (!$sister) {
            $item = $this->getParentNode()->getNextSisterWithMouvementCheck();
            if ($item) {
               
               $sister = $item->getChildrenNode()->getFirst();
            }
        }

        if ($sister && !$sister->hasMouvementCheck()) {

            return $sister->getNextSisterWithMouvementCheck();
        }

        return $sister;
    }

    public function getProduits() {
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits = array_merge($produits, $item->getProduits());
        }

        return $produits;
    }

    public function getProduitsLibelle($format = "%g% %a% %m% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {
        $produits = $this->getProduits();
        $produits_format = array();
        foreach($produits as $key => $produit) {
            $produits_format[$key] = $produit->getLibelle($format);
        }

        return $produits_format;
    }

    public function getLieuxArray() {
        $lieux = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $lieux = array_merge($lieux, $item->getLieuxArray());
        }

        return $lieux;
    }
  
    abstract public function getChildrenNode();

}