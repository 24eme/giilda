<?php
/**
 * Model for Facture
 *
 */

class Facture extends BaseFacture {

    private $documents_origine = array();
    
    public function getDocumentsOrigine() {
        return $this->documents_origine;
    }
    
    public function save() {
        if($this->isNew()){
            $this->facturerMouvements();
            $this->saveDocumentsOrigine();
        }
        parent::save();
    }


    public function saveDocumentsOrigine()
    {
        foreach ($this->getDocumentsOrigine() as $doc) {
            $doc->save();
        }
    }

    public function getDocumentOrigine($id) {
        
        if(!array_key_exists($id, $this->documents_origine)) {            
            $this->documents_origine[$id] = acCouchdbManager::getClient()->find($id);
        }
        
        return $this->documents_origine[$id];
    }
    
    public function facturerMouvements()
    {
        foreach ($this->getLignes() as $l) {
           $l->facturerMouvement();
        }
    }            
    
    public function getEcheances() {
        $e = $this->_get('echeances')->toArray();
        usort($e, 'Facture::triEcheanceDate');
        return $e;
    }
    
    public function getLignes() {
        $l = $this->_get('lignes')->toArray();
        usort($l, 'Facture::triOrigineDate');
        return $l;
    }
    
    static function triOrigineDate($ligne_0, $ligne_1) {
            return self::triDate("origine_date", $ligne_0, $ligne_1);
    }
    
    static function triEcheanceDate($ligne_0, $ligne_1) {
            return self::triDate("echeance_date", $ligne_0, $ligne_1);
    }
 
    static function triDate($champ, $ligne_0, $ligne_1)
    {
        if ($ligne_0->{$champ} == $ligne_1->{$champ}) {

        return 0;
        }
        return ($ligne_0->{$champ} > $ligne_1->{$champ}) ? -1 : +1;
    }
    
    public function getLignesPropriete() {
        return $this->getFactureLignesByMouvementType(FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE);
    }
    
    public function getLignesContrat() {
        return $this->getFactureLignesByMouvementType(FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT);
    }
    
    public function getLignesContratType($type) {
        $contrats =  $this->getFactureLignesByMouvementType(FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT);
        $lignesByType = array();
        foreach ($contrats as $ligne) 
        {
            if($ligne->produit_type == $type)
            {
                $lignesByType[] = $ligne;
            }  
        }
        return $lignesByType;
    }
    
    
    public function getLignesProduits($propriete){
        $produits = array();
        
        foreach ($propriete as $prop)
        {
            if(array_key_exists($prop->produit_hash, $produits))
            {               
                $produits[$prop->produit_hash][] = $prop;                
            }
            else
            {
                $produits[$prop->produit_hash] = array();
                $produits[$prop->produit_hash][] = $prop;                
            }
        }
        return $produits;
    }




    private function getFactureLignesByMouvementType($mouvement_type)
    {
        $lignesByMouvementType = array();
        foreach ($this->getLignes() as $ligne) 
        {
            if($ligne->mouvement_type == $mouvement_type)
            {
                $lignesByMouvementType[] = $ligne;
            }  
        }
        return $lignesByMouvementType;
    }
    
    
}