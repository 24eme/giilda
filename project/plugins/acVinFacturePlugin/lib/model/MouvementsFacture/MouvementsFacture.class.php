<?php

/**
 * Model for MouvementsFacture
 *
 */
class MouvementsFacture extends BaseMouvementsFacture {

    public function constructIds($date) {
        if (!$date) {
            $date = date('Ymd');
        }

        $this->identifiant = MouvementsFactureClient::getInstance()->getNextNoMouvementsFacture($date);
        $this->periode = substr($date, 0, 6);
        $this->_id = MouvementsFactureClient::getInstance()->getId($this->identifiant);
    }

    public function getNbMvts() {
        $nb_mvt = 0;
        foreach ($this->mouvements as $etbKey => $mvtsEtb) {
            $nb_mvt += count($mvtsEtb);
        }
        return $nb_mvt;
    }

    public function getNbSocietes() {
        return count($this->mouvements);
    }

    public function getTotalHtAFacture() {
        $montant = 0;
        foreach ($this->mouvements as $etbKey => $mvtsEtb) {
            foreach ($mvtsEtb as $mvtKey => $mvt) {
                if($mvt->facturable && !$mvt->facture){
                    $montant += $mvt->quantite *  $mvt->prix_unitaire;
                } 
            }
        }
        return $montant;
    }
    
    public function getLibelleFromId() {
        return "Facturation libre : ".$this->getLibelle()." (".Date::francizeDate($this->getDate()).")";
    }

}
