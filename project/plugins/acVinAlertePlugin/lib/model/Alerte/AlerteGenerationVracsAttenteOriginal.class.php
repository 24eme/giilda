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
        $vracs = array();
        $vracs = VracClient::getInstance()->retreiveByWaitForOriginal();
        foreach ($vracs as $vrac) {
            $date_saisie = $vrac->key[VracOriginalPrixDefinitifView::KEY_DATE_SAISIE];
            $date_limit = $this->getConfigOptionDelaiDate('creation_date', $date_saisie);
            if (Date::supEqual($this->getDate(), $date_limit)) {
                $alerte = $this->createOrFind($vrac->id, $vrac->key[VracOriginalPrixDefinitifView::KEY_IDENTIFIANT], $vrac->key[VracOriginalPrixDefinitifView::KEY_NOM]);
                if (!$alerte->isNew() && $alerte->isClosed()) {
                    $alerte->open();
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

        foreach ($this->getAlertesRelancable() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            $dateLastRelance = $alerte->getLastDateARelance();
            if ($dateLastRelance) {
                $dateRelance = $this->getConfigOptionDelaiDate('relances_suivantes', $dateLastRelance);
            } else {
                $dateRelance = $this->getConfigOptionDelaiDate('relance_delai_premiere', $alerte->date_creation);
            }
            if (Date::supEqual($this->getDate(), $dateRelance)) {
                $alerte->updateStatut(AlerteClient::STATUT_ARELANCER, 'Changement automatique au statut à relancer', $this->getDate());
                $alerte->save();
            }
        }
    }

}