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

        $last_periode = DRMDerniereView::getInstance()->findLastPeriode();

        if(!$last_periode) {

            return null;
        }

        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproAndStatut('INTERPRO-inter-loire', EtablissementClient::STATUT_ACTIF);
        $periodes = $this->getPeriodes();

        foreach($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);
            echo $etablissement->identifiant."\n";
            foreach($periodes as $periode) {
                $drm = DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($etablissement->identifiant, $periode), acCouchdbClient::HYDRATE_JSON);

                if($drm) {

                    continue;
                }

                $alerte = $this->createOrFindByDRM($this->buildDRMManquante($etablissement->identifiant, $periode));
                if(!($alerte->isNew() || $alerte->isClosed())) {
                
                    continue;
                }
                $alerte->open($this->getDate());
                $alerte->save();

                echo $identifiant;
                echo $periode;
            }
        }
    }

    protected function getPeriodes() {
        return array('201208', '201209', '201210', '200501', '200401', '200301', '200506');
    }

    public function buildDRMManquante($identifiant, $periode) {
        $id = DRMClient::getInstance()->buildId($identifiant, $periode);
        $drm_manquante = new stdClass();
        $drm_manquante->identifiant = $identifiant;
        $drm_manquante->periode = $periode;
        $drm_manquante->version = null;
        $drm_manquante->_id = $id;

        return $drm_manquante;
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            /*$id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $vrac = VracClient::getInstance()->find($id_document);
            if (isset($vrac)) {
                if (!$vrac->getOriginal()) {
                    $alerte = AlerteClient::getInstance()->find($alerteView->id);
                    $alerte->updateStatut(AlerteClient::STATUT_FERME, 'Changement automatique au statut fermer', $this->getDate());
                    $alerte->save();
                    continue;
                }
            }*/
        }
        parent::updates();
    }
}