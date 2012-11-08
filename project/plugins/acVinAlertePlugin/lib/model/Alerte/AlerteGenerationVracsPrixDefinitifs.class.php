<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsPrixDefinitifs
 * @author mathurin
 */
class AlerteGenerationVracsPrixDefinitifs extends AlerteGeneration {


    public function getTypeAlerte() {

        return AlerteClient::VRAC_PRIX_DEFINITIFS;
    }

    public function creations() {
        $vracs = array();
        if (Date::supEqual($this->getDate(), $this->getConfigOptionDate('creation_date'))) {
            $vracs = VracClient::getInstance()->findContatsByWaitForPrixDefinitif($this->getDate());
        }
        foreach ($vracs as $vrac) {
            $alerte = $this->createOrFind($vrac->id, $vrac->key[VracOriginalPrixDefinitifView::KEY_IDENTIFIANT], $vrac->key[VracOriginalPrixDefinitifView::KEY_NOM]);
            if (!$alerte->isNew() && $alerte->isClosed()) {
                $alerte->open();
            }
            $alerte->save();
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $vrac = VracClient::getInstance()->find($id_document);
            if (isset($vrac)) {
                if ($vrac->prixDefinitifExist()) {
                    $alerte = AlerteClient::getInstance()->find($alerteView->id);
                    $alerte->updateStatut(AlerteClient::STATUT_FERME,'Changement automatique au statut fermer', $this->getDate());
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