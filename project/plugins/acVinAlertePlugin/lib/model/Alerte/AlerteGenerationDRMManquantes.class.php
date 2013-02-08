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

    protected $last_periode = null;

    public function getTypeAlerte() {

        return AlerteClient::DRM_MANQUANTE;
    }

    public function creations() {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproAndStatut('INTERPRO-inter-loire', EtablissementClient::STATUT_ACTIF);
        $periodes = $this->getPeriodes();

        foreach($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);

            if($etablissement->type_dr != EtablissementClient::TYPE_DR_DRM) {

                continue;
            } 

            foreach($periodes as $periode) {
                $drm = DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($etablissement->identifiant, $periode), acCouchdbClient::HYDRATE_JSON);

                if($drm) {

                    continue;
                }

                $alerte = $this->createOrFindByDRM($this->buildDRMManquante($etablissement, $periode));
                if(!($alerte->isNew() || $alerte->isClosed())) {
                
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

            $drm = DRMClient::getInstance()->find($id_document, acCouchdbClient::HYDRATE_JSON);
            if(!$drm)  {

                continue;
            } 

            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            $alerte->updateStatut(AlerteClient::STATUT_FERME, AlerteClient::MESSAGE_AUTO_FERME, $this->getDate());
            $alerte->save();
            echo $id_document.":closed\n";
        }
        parent::updates();
    }

    protected function getPeriodes() {
        $campagnes = $this->getCampagnes();

        $periode_debut = ConfigurationClient::getInstance()->getPeriodeDebut($campagnes[count($campagnes) -1]);
        $periode_fin = $this->getLastPeriode();
        $date_fin = $this->getConfig()->getOptionDelaiDate('creation_delai', ConfigurationClient::getInstance()->buildDate($periode_fin));
        $periode_fin = ConfigurationClient::getInstance()->buildPeriodeFromDate($date_fin);

        $periodes = array();

        while($periode_debut <= $periode_fin) {
            $periodes[] = $periode_debut;

            $periode_debut = ConfigurationClient::getInstance()->getPeriodeSuivante($periode_debut);
        }


        return $periodes;
    }

    protected function getLastPeriode() {
        if(is_null($this->last_periode)) {

            $this->last_periode = DRMDerniereView::getInstance()->findLastPeriode();
        }

        if(!$this->last_periode) {

            throw new sfException("Pas de DRMs");
        }

        return $this->last_periode;
    }

    protected function getCampagnes() {
        $nb_campagne = $this->getConfig()->getOption('nb_campagne');

        $last_periode = $this->getLastPeriode();

        $campagne = ConfigurationClient::getInstance()->buildCampagneByPeriode($last_periode);
        $campagnes = array();

        for($i=$nb_campagne;$i>0;$i--) {
            preg_match('/([0-9]{4})-([0-9]{4})/', $campagne, $annees);
            $campagnes[] = sprintf("%s-%s", $annees[1]-$i, $annees[2]-$i);
        }

        return $campagnes;
    }

    protected function buildDRMManquante($etablissement, $periode) {
        $id = DRMClient::getInstance()->buildId($etablissement->identifiant, $periode);
        $drm_manquante = new stdClass();

        $drm_manquante->identifiant = $etablissement->identifiant;
        $drm_manquante->periode = $periode;
        $drm_manquante->campagne = ConfigurationClient::getInstance()->buildCampagneByPeriode($periode);
        $drm_manquante->version = null;
        $drm_manquante->declarant = new stdClass();
        $drm_manquante->declarant->region = $etablissement->region;
        $drm_manquante->declarant->nom = $etablissement->nom;
        $drm_manquante->_id = $id;

        return $drm_manquante;
    }

    public function creationsByDocumentsIds(array $documents_id) {
        
    }

    public function execute() {
        $this->updates();
        $this->creations();
    }

    public function isInAlerte($document) {
        
    }

    public function updatesByDocumentsIds(array $documents_id) {
        
    }

  
}