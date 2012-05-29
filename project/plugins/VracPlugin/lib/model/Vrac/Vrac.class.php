<?php
/**
 * Model for Vrac
 *
 */

class Vrac extends BaseVrac {
    public function constructId() {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);
    }
    
    public function update($params = array()) {
        
        switch ($this->type_transaction)
        {
            case 'raisins' :
            {
                $this->prix_total = $this->raisin_quantite * $this->prix_unitaire;
                break;
            }
            
            case 'mouts' :
            {
                $this->prix_total = $this->jus_quantite * $this->prix_unitaire;                
                break;
            } 
            
            case 'vin_vrac' :
            {
                $this->prix_total = $this->jus_quantite * $this->prix_unitaire;              
                break;
            }  
            
            case 'vin_bouteille' :
            {
                $this->prix_total = $this->bouteilles_quantite * $this->prix_unitaire;
                break;
            }
            default :
                $this->prix_total = null;
        }
        
    }
}