<?php
/**
 * Model for DRMCrd
 *
 */

class DRMCrd extends BaseDRMCrd {
    
    public function getType() {
	return $this->getParent()->getKey();
    }

    public function getLibelle(){
        return DRMClient::$drm_crds_couleurs[$this->couleur].' - '.str_replace('Bouteille', '', $this->detail_libelle);
    }
    
     public function getShortLibelle(){
        return DRMClient::$drm_crds_couleurs[$this->couleur].' '.str_replace('Bouteille', '', $this->detail_libelle);
    }
    
    public function udpateStockFinDeMois() { 
        $this->stock_fin = $this->stock_debut + $this->entrees_achats + $this->entrees_retours + $this->entrees_excedents - $this->sorties_utilisations - $this->sorties_destructions - $this->sorties_manquants;
    }
}