<?php
/**
 * Model for Alerte
 *
 */

class Alerte extends BaseAlerte {
    protected function constructId() {
        $this->_id = AlerteClient::getInstance()->buildId($this->type_alerte, $this->id_document);
        $this->date_creation = date('Y-m-d');
        $this->open();
    }
    
    public function open() {
        $this->updateStatut(AlerteClient::STATUT_NOUVEAU);
    }
    
    public function getLastDateARelance() {
            $cpt = count($this->statuts)-1;
            while ($cpt)
            {
                if($this->statuts[$cpt]->statut == AlerteClient::STATUT_ARELANCER) return $this->statuts[$cpt]->date;
                $cpt--;
            }
            return null;
    }


    public function updateStatut($statut, $commentaire = null, $date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }
        $this->statuts->add(null, array('statut' => $statut, 'commentaire' => $commentaire, 'date' => $date));
    }
    
    public function getStatut(){
        
        return $this->statuts->getLast();
    }
    
    public function isOpen() {
        
        return !$this->isFinished();
    }
            
    public function isFinished() {
        
        return in_array($this->getStatut()->statut, array(AlerteClient::STATUT_FERME, AlerteClient::STATUT_RESOLU));
    }
    
    public function isClosed() {
        
        return $this->getStatut()->statut == AlerteClient::STATUT_FERME;
    }
}