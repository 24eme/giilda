<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteSV12SansVrac
 * @author mathurin
 */
class AlerteGenerationSV12SansVrac extends AlerteGenerationSV12 {

    public function getTypeAlerte() {

        return AlerteClient::SV12_SANS_VRAC;
    }

    public function creations() {
        $rows = SV12Client::getInstance()->retreiveSV12s();
        $prec_sv12 = null;
        foreach ($rows as $row) {
            $sv12 = SV12Client::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
            if (($sv12->valide->statut != SV12Client::STATUT_VALIDE) || (!Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_delai', $this->getDate()), $sv12->valide->date_saisie))) {
                continue;
            }
            if ((!$prec_sv12) || ($prec_sv12->identifiant != $sv12->identifiant) || ($prec_sv12->periode != $sv12->periode)) {            
                $prec_sv12 = $this->createAlerteSV12SansContrat($sv12);
           }
        }
    }

    private function createAlerteSV12SansContrat($sv12) {
        foreach ($sv12->contrats as $key => $contrat) {
            if ((substr($key, 0, strlen(SV12Client::SV12_KEY_SANSCONTRAT)) == SV12Client::SV12_KEY_SANSCONTRAT)) {
                $this->createAlerteForSv12($sv12);
                return $sv12;
            } elseif (substr($key, 0, strlen(SV12Client::SV12_KEY_SANSVITI)) == SV12Client::SV12_KEY_SANSVITI) {
                $this->createAlerteForSv12($sv12);
                return $sv12;
            }
        }
        return null;
    }

    private function createAlerteForSv12($sv12) {
        $alerte = $this->createOrFindBySV12($sv12);
        if ($alerte->isNew() || $alerte->isClosed()) {
            $alerte->open($this->getDate());
        }
        $alerte->save();
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $sv12 = SV12Client::getInstance()->find($id_document, acCouchdbClient::HYDRATE_JSON);
            if (!$sv12) {

                continue;
            }
            $exist_sans_contrat = false;
            foreach ($sv12->contrats as $key => $contrat) {
                if (substr($key, 0, strlen(SV12Client::SV12_KEY_SANSCONTRAT)) == SV12Client::SV12_KEY_SANSCONTRAT) {
                    $exist_sans_contrat = true;
                    break;
                }
                if (substr($key, 0, strlen(SV12Client::SV12_KEY_SANSVITI)) == SV12Client::SV12_KEY_SANSVITI) {
                    $exist_sans_contrat = true;
                    break;
                }
            }
            if (!$exist_sans_contrat) {
                $alerte = AlerteClient::getInstance()->find($alerteView->id);
                $alerte->updateStatut(AlerteClient::STATUT_FERME, AlerteClient::MESSAGE_AUTO_FERME, $this->getDate());
                $alerte->save();
            }
            parent::updates();
        }
    }

}