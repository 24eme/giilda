<?php
/**
 * Model for Facture
 *
 */

class Facture extends BaseFacture {

    private $documents_origine = array();
    
    public function constructId() {
      $this->identifiant = FactureClient::getInstance()->getNextNoFacture($this->client_identifiant,date('Ymd'));
      $this->_id = 'FACTURE-' . $this->client_reference . '-' . $this->identifiant;
    }
    
    public function getDocumentsOrigine() {
        return $this->documents_origine;
    }
    
    public function save() {
        if($this->isNew()){
            $this->facturerMouvements();
        }
        parent::save();
        $this->saveDocumentsOrigine();
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
                   $l->facturerMouvements();
        }
    }  
    
    public function defacturer() {
        foreach ($this->getLignes() as $ligne) {
            $ligne->defacturerMouvements();
        }
        $this->statut = FactureClient::STATUT_REDRESSEE;
        
    }


    public function getEcheances() {
        $e = $this->_get('echeances')->toArray();
        usort($e, 'Facture::triEcheanceDate');
        return $e;
    }
    
    public function getLignesArray() {
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
  
    
}