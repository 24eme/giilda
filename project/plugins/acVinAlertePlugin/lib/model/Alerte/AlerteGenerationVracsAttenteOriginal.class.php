<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
class AlerteGenerationVracsAttenteOriginal extends AlerteGenerationVrac {

    public function getTypeAlerte() {

        return AlerteClient::VRAC_ATTENTE_ORIGINAL;
    }

    public function creations() {
        $rows = VracClient::getInstance()->retreiveByWaitForOriginal();
        foreach ($rows as $row) {
            if (!Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_date', $this->getDate()), $row->key[VracOriginalPrixDefinitifView::KEY_DATE_SAISIE])) {

                continue;
            }

            $vrac = VracClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
            $alerte = $this->createOrFindByVrac($vrac);

            if ($alerte->isNew() || $alerte->isClosed()) {
                $alerte->open($this->getDate());
            }
            $alerte->save();
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $vrac = VracClient::getInstance()->find($id_document, acCouchdbClient::HYDRATE_JSON);
            if (!$vrac) {

                continue;
            }

            if ($vrac->attente_original) {

                continue;
            }

            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            $alerte->updateStatut(AlerteClient::STATUT_FERME, 'Changement automatique au statut fermer', $this->getDate());
            $alerte->save();
        }
        parent::updates();
    }

}