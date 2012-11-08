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


    public function getTypeAlerte() {

        return AlerteClient::VRAC_NON_SOLDES;
    }

    public function creations() {
        $vracs = array();
        if ($this->getConfigOptionDate('creation_date') == $this->getDate()) {
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
            $alerte = $this->createOrFind($vrac->id, $vrac->key[VracStatutAndTypeView::KEY_IDENTIFIANT], $vrac->key[VracStatutAndTypeView::KEY_NOM]);
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
                if ($vrac->isSolde()) {
                    $alerte = AlerteClient::getInstance()->find($alerteView->id);
                    $alerte->updateStatut(AlerteClient::STATUT_FERME);
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