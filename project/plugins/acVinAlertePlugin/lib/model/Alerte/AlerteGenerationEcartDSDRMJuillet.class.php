<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationEcartDSDRMJuillet
 * @author mathurin
 */
class AlerteGenerationEcartDSDRMJuillet extends AlerteGenerationDS {

    public function getTypeAlerte() {
        return AlerteClient::ECART_DS_DRM_JUILLET;
    }

    public function creations() {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-inter-loire', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_PRODUCTEUR));
        $campagnes = $this->getCampagnes();
        foreach ($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);
            foreach ($campagnes as $campagne) {
                $drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($etablissement->identifiant, $campagne);
                if (!$drm || ($drm->getMois() != 7))
                    continue;
                $drm_master = $drm->findMaster();
                $ds = DSClient::getInstance()->findLastByIdentifiant($etablissement->identifiant);
                if(!$ds || ($ds->getPeriode() > $drm_master->getPeriode() )){
                    continue;
                }
                if($this->isInAlerte($ds)){
                    $alerte = $this->createOrFindByDS($this->buildEcartDSDRMJuillet($etablissement, $ds));
                    $alerte->open($this->getDate());
                    $alerte->type_relance = $this->getTypeRelance();
                    $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER,'Alerte mis en statut à relancer automatiquement',  $this->getDate());
                    $alerte->save();
                }
            }
            
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $ds = DSClient::getInstance()->find($id_document);
            if ($this->isInAlerte($ds)) {
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

    protected function getCampagnes() {
        $nb_campagne = $this->getConfig()->getOption('nb_campagne');

        $campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        $campagnes = array();

        for ($i = $nb_campagne-1; $i >= 0; $i--) {
            preg_match('/([0-9]{4})-([0-9]{4})/', $campagne, $annees);
            $campagnes[] = sprintf("%s-%s", $annees[1] - $i, $annees[2] - $i);
        }

        return $campagnes;
    }

    protected function buildEcartDSDRMJuillet($etablissement, $ds) {
        
        $ds_ecart = new stdClass();

        $ds_ecart->identifiant = $etablissement->identifiant;
        $ds_ecart->periode = $ds->periode;
        $ds_ecart->campagne = $ds->campagne;
        $ds_ecart->version = null;
        $ds_ecart->declarant = new stdClass();
        $ds_ecart->declarant->region = $etablissement->region;
        $ds_ecart->declarant->nom = $etablissement->nom;
        $ds_ecart->_id = $ds->_id;

        return $ds_ecart;
    }

    public function creationsByDocumentsIds(array $documents_id, $document_type) {
        
    }

    public function execute() {
        $this->updates();
        $this->creations();
    }

    public function isInAlerte($document) {
        if(!$document) return false;
        foreach ($document->declarations as $hashKey => $declaration) {
            $diff = $declaration->stock_initial - $declaration->stock_declare;
            $seuil = $this->getConfig()->getOption('seuil');
            if (abs($diff) >  (abs($declaration->stock_initial) * ($seuil/100)))                
                return true;
        }
        return false;
    }

    public function updatesByDocumentsIds(array $documents_id, $document_type) {
        
    }

    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_ECART;
    }
}