<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
class AlerteGenerationDRMManquantes extends AlerteGenerationDRM {

    public function getTypeAlerte() {

        return AlerteClient::DRM_MANQUANTE;
    }

    public function creations() {
        $drms = DRMAllView::getInstance()->findAll();

        $drm_prec = null;
        
        foreach($drms as $drm) {
            if(($drm_prec) && ($drm_prec->identifiant == $drm->identifiant && $drm_prec->periode == $drm->periode)) {
                // On n'exclut le versionnage d'une DRM
                continue;
            }

            if(($drm_prec) && ($drm_prec->identifiant != $drm->identifiant)) {

                $drm_prec = null;
            }

            $this->createAlertesUntilDate($drm);

            if(!$drm_prec) {
                $drm_prec = $drm;

                continue;
            }

            $this->createAlertesATrou($drm_prec, $drm);
         
            $drm_prec = $drm;
        }
    }

    protected function createAlertesUntilDate($drm) {
        $periode = DRMClient::getInstance()->getPeriodeSuivante($drm->periode); 
        while(DRMClient::getInstance()->buildDate($periode) < date('Y-m-d')) {
            $alerte = $this->createOrFindByDRM($this->buildDRMManquante($drm, $periode));
            if(!$alerte->isNew() && !$alerte->isClosed()) {
                
                continue;
            }
            $alerte->open($this->getDate());
            $alerte->save();

            echo $drm->identifiant.$periode ."\n";

            $periode = DRMClient::getInstance()->getPeriodeSuivante($periode);
        }
    }

    protected function createAlertesATrou($drm_prec, $drm) {
        $periode = DRMClient::getPeriodeSuivante($drm->periode);
        while($drm_prec->periode != $periode) {
            if(DRMClient::buildDate($periode) > date('Y-m-d')) {
                continue;
            }

            $alerte = $this->createOrFindByDRM($this->buildDRMManquante($drm, $periode));

            if(!$alerte->isNew() && !$alerte->isClosed()) {
                
                continue;
            }
            $alerte->open($this->getDate());
            $alerte->save();

            echo $drm->identifiant.$periode ."\n";

            $periode = DRMClient::getInstance()->getPeriodeSuivante($drm->periode); 
        }
    }

    public function buildDRMManquante($drm, $periode) {
        $drm_manquante = DRMClient::getInstance()->find($drm->_id, acCouchdbClient::HYDRATE_JSON);
        $drm_manquante->periode = $periode;
        $drm_manquante->version = null;
        $drm_manquante->_id = DRMClient::getInstance()->buildId($drm->identifiant, $periode);

        return $drm_manquante;
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