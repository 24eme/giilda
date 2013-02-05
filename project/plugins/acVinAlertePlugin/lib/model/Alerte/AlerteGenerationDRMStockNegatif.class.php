<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationDRMStockNegatif
 * @author mathurin
 */
class AlerteGenerationDRMStockNegatif extends AlerteGenerationDRM {

    public function getTypeAlerte() {

        return AlerteClient::DRM_STOCK_NEGATIF;
    }

    public function creations() {

        $rows = DRMAllView::getInstance()->findAll();

        foreach ($rows as $row) {
            $drm = DRMClient::getInstance()->find($row->_id);
            if (!$drm) {
                return null;
            }
            $hasNegatifTotal = false;
            foreach ($drm->getProduitsDetails() as $prod) {
                if ($prod->getCepage()->getTotal() < 0) {
                    $hasNegatifTotal = true;
                    break;
                }
            }
            if ($hasNegatifTotal) {
                $alerte = $this->createOrFindByDRM($drm);
                if (!($alerte->isNew() || $alerte->isClosed())) {

                    continue;
                }
                $alerte->open($this->getDate());
                $alerte->save();
            }
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $drm = DRMClient::getInstance()->find($id_document);
            if (isset($drm)) {
                $drm_rec = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($drm->identifiant,$drm->periode);
                if($drm_rec->version != $drm->version){
                    $alerte = AlerteClient::getInstance()->find($alerteView->id);
                    $alerte->updateStatut(AlerteClient::STATUT_FERME, 'Changement automatique au statut fermer', $this->getDate());
                    $alerte->save();
                    continue;
                }
                $hasNegatifTotal = false;
                foreach ($drm->getProduitsDetails() as $prod) {
                    if ($prod->getCepage()->getTotal() < 0) {
                        $hasNegatifTotal = true;
                        break;
                    }
                }
                if (!$hasNegatifTotal) {
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