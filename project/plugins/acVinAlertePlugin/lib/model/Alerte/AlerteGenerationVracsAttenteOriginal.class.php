<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
class AlerteGenerationVracsAttenteOriginal extends AlerteGeneration {

    public function getTypeAlerte() {

        return AlerteClient::VRAC_ATTENTE_ORIGINAL;
    }

    public function creations() {
       $vracs = VracClient::getInstance()->retreiveByWaitForOriginal();
        foreach ($vracs as $vrac) {
            if(Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_date',$this->getDate()),
                              $vrac->key[VracOriginalPrixDefinitifView::KEY_DATE_SAISIE])) {
                $alerte = $this->createOrFind($vrac->id, $vrac->key[VracOriginalPrixDefinitifView::KEY_IDENTIFIANT], $vrac->key[VracOriginalPrixDefinitifView::KEY_NOM]);
               if($alerte->isNew() || $alerte->isClosed()) {
                   $alerte->open($this->getDate());
                }
                $alerte->save();
            }
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $vrac = VracClient::getInstance()->find($id_document);
            if (isset($vrac)) {
                if (!$vrac->getOriginal()) {
                        $alerte = AlerteClient::getInstance()->find($alerteView->id);
                        $alerte->updateStatut(AlerteClient::STATUT_FERME, 'Changement automatique au statut fermer', $this->getDate());
                        $alerte->save();
                    continue;
                }
            }
        }
        parent::updates();
    }

}