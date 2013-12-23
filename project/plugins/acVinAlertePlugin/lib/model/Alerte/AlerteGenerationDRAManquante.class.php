<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
class AlerteGenerationDRAManquante extends AlerteGenerationDRM {

    public function getTypeAlerte() {

        return AlerteClient::DRA_MANQUANTE;
    }

    public function creations() {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-inter-loire', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_PRODUCTEUR));
        
        $periodes_by_campagnes = $this->getPeriodesByCampagnes();
        foreach ($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);

            if ($etablissement->type_dr != EtablissementClient::TYPE_DR_DRA) {

                continue;
            }


            foreach ($periodes_by_campagnes as $campagne => $periodes_by_campagne) {
                if (!$this->isDraInCampagneArray($etablissement->identifiant,$periodes_by_campagne)) {
                    $alerte = $this->createOrFindByDRM($this->buildDRAManquante($etablissement, $campagne));
                    $alerte->type_relance = $this->getTypeRelance();
                    if (!($alerte->isNew() || $alerte->isClosed())) {
                        continue;
                    }
                    $alerte->open($this->getDate());
                    $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER,'Alerte mis en statut à relancer automatiquement',  $this->getDate());
                    $alerte->save();
                }
            }
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            if ($this->isInAlerteView($alerteView)) {
                $relance = Date::supEqual($this->getDate(), $alerte->date_relance);
                if ($relance) {
                    $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, null, $this->getDate());
                    $alerte->save();
                }
                continue;
            }
            $alerte->updateStatut(AlerteClient::STATUT_FERME, AlerteClient::MESSAGE_AUTO_FERME, $this->getDate());
            $alerte->save();
        }
    }

    protected function buildDRAManquante($etablissement, $campagne) {
        $periode = ConfigurationClient::getInstance()->getPeriodeDebut($campagne);
        $id = DRMClient::getInstance()->buildId($etablissement->identifiant, $periode);
        $dra_manquante = new stdClass();

        $dra_manquante->identifiant = $etablissement->identifiant;
        $dra_manquante->periode = $periode;
        $dra_manquante->campagne = $campagne;
        $dra_manquante->version = null;
        $dra_manquante->declarant = new stdClass();
        $dra_manquante->declarant->region = $etablissement->region;
        $dra_manquante->declarant->nom = $etablissement->nom;
        $dra_manquante->_id = $id;
        return $dra_manquante;
    }

    public function creationsByDocumentsIds(array $documents_id,$document_type) {
        
    }

    public function execute() {
        $this->updates();
        $this->creations();
    }

    public function isInAlerteView($view) {
        $id_document = $view->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
        if (preg_match('/^DRM-([0-9]{8})-([0-9]{6})([0-9-]*)/', $id_document, $matches)) {
            $identifiant = $matches[1];
            $periode = $matches[2];
        }
        $campagne = ConfigurationClient::getInstance()->buildCampagneByPeriode($periode);
        return !$this->isDraInCampagneArray($identifiant,$this->getPeriodesByCampagne($campagne));
    }

    public function isDraInCampagneArray($identifiant,$periodes_by_campagne) {
        foreach ($periodes_by_campagne as $periode) {
            $drm = DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($identifiant, $periode), acCouchdbClient::HYDRATE_JSON);
            if ($drm) {
                return true;
            }
        }
        return false;
    }

    public function updatesByDocumentsIds(array $documents_id,$document_type) {
        
    }

    protected function getPeriodesByCampagnes() {
        $campagnes = $this->getCampagnes();
        $periodes_by_campagnes = array();
        foreach ($campagnes as $campagne) {
            $periodes_by_campagnes[$campagne] = $this->getPeriodesByCampagne($campagne);
        }
        return $periodes_by_campagnes;
    }

    protected function getPeriodesByCampagne($campagne) {
        $periodes_by_campagne = array();
        $periode_debut = ConfigurationClient::getInstance()->getPeriodeDebut($campagne);
        $periode_fin = ConfigurationClient::getInstance()->getPeriodeFin($campagne);
        while ($periode_debut <= $periode_fin) {
            $periodes_by_campagne[$campagne][] = $periode_debut;
            $periode_debut = ConfigurationClient::getInstance()->getPeriodeSuivante($periode_debut);
        }
        return $periodes_by_campagne;
    }

    protected function getCampagnes() {
        $nb_campagne = $this->getConfig()->getOption('nb_campagne');

        $last_periode = $this->getLastPeriode();

        $campagne = ConfigurationClient::getInstance()->buildCampagneByPeriode($last_periode);
        $campagnes = array();

        for ($i = $nb_campagne; $i > 0; $i--) {
            preg_match('/([0-9]{4})-([0-9]{4})/', $campagne, $annees);
            $campagnes[] = sprintf("%s-%s", $annees[1] - $i, $annees[2] - $i);
        }

        return $campagnes;
    }

    protected function getLastPeriode() {
        if (is_null($this->last_periode)) {

            $this->last_periode = DRMDerniereView::getInstance()->findLastPeriode();
        }

        if (!$this->last_periode) {

            throw new sfException("Pas de DRMs");
        }

        return $this->last_periode;
    }

    public function isInAlerte($document) {
        
    }

    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_DECLARATIVE;
    }
    
}