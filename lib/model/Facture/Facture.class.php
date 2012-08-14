<?php
/**
 * Model for Facture
 *
 */

class Facture extends BaseFacture {

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