<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationContratsNonSoldes
 * @author mathurin
 */
class AlerteGenerationVracsNonSoldes extends AlerteGeneration {

    public $date = '2010-01-07';

    public function getTypeAlerte() {

        return AlerteClient::VRAC_NON_SOLDES;
    }

    public function creations() {
        if($this->getConfigOptionDate('creation_date') == $this->getDate()) {
            $vracs = VracClient::getInstance()->retreiveByStatutsTypes(
                    array(VracClient::STATUS_CONTRAT_NONSOLDE), array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
                    VracClient::TYPE_TRANSACTION_VIN_VRAC)
            );
        } else {
            $vracs = VracClient::getInstance()->retreiveByStatutsTypesAndDate(
                    array(VracClient::STATUS_CONTRAT_NONSOLDE), array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
                    VracClient::TYPE_TRANSACTION_VIN_VRAC), $this->getConfigOptionDelaiDate('creation_delai', $this->getDate())
            );
        }
        
        foreach ($vracs as $vrac) {
            $alerte = $this->createOrFind($vrac->id, $vrac->key[VracStatutAndTypeView::KEY_IDENTIFIANT],$vrac->key[VracStatutAndTypeView::KEY_NOM]);
            if (!$alerte->isNew() && $alerte->isFinished()) {
                $alerte->open();
            }
            $alerte->save();
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $vrac = VracClient::getInstance()->find($id_document);
            if($vrac->isSolder()){
                $alerte = AlerteClient::getInstance()->find($alerteView->id);
                $alerte->updateStatut(AlerteClient::STATUT_FERME);
                $alerte->save();
                continue;
            }
        }
        
        foreach($this->getAlertesRelancable() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);
        }
    }
    
    public function getDate() {
        
        return $this->date; // return date('Y-m-d');
    }
}