<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationEcartDSDRMAout
 * @author mathurin
 */
class AlerteGenerationEcartDSDRMAout extends AlerteGenerationDS {

    public function getTypeAlerte() {
        return AlerteClient::ECART_DS_DRM_AOUT;
    }

    public function creations() {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-declaration', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_PRODUCTEUR));
        $campagnes = $this->getCampagnes();
        foreach ($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);
            foreach ($campagnes as $campagne) {
                $periode = substr($campagne, 5,4).'08';
                $drm_master = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement->identifiant, $periode);
                if (!$drm_master || ($drm_master->getMois() != 8))
                    continue;
                $ds = DSClient::getInstance()->findLastByIdentifiant($etablissement->identifiant);
                if(!$ds || $ds->getCampagne() != $drm_master->getCampagne()){
                    continue;
                }
                if($this->isInAlerteWithDRM($ds,$drm_master)){
                    $alerte = $this->createOrFindByDS($this->buildEcartDSDRMAout($etablissement, $ds));
                    if (!($alerte->isNew() || $alerte->isClosed())) {
                        continue;
                    }
                    $alerte->open($this->getDate());
                    $alerte->type_relance = $this->getTypeRelance();
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
            $periode_drm = substr($alerte->campagne, 5).'08';
            $drm_master = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($alerteView->key[AlerteHistoryView::KEY_IDENTIFIANT], $periode_drm);
            if ($this->isInAlerteWithDRM($ds,$drm_master)) {
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
        $campagne = ConfigurationClient::getInstance()->getNextCampagne($campagne);
        $campagnes = array();

        for ($i = $nb_campagne-1; $i >= 0; $i--) {
            preg_match('/([0-9]{4})-([0-9]{4})/', $campagne, $annees);
            $campagnes[] = sprintf("%s-%s", $annees[1] - $i, $annees[2] - $i);
        }

        return $campagnes;
    }

    protected function buildEcartDSDRMAout($etablissement, $ds) {
        $ds_ecart = new stdClass();
        $ds_ecart->identifiant = $etablissement->identifiant;
        $ds_ecart->periode = $ds->periode;
        $ds_ecart->campagne = $ds->campagne;
        $ds_ecart->version = null;
        $ds_ecart->declarant = new stdClass();
        $ds_ecart->declarant->region = $etablissement->region;
        $ds_ecart->declarant->nom = $etablissement->nom;
        $ds_ecart->_id = $ds->_id;
        $ds_ecart->type = $ds->type;
        return $ds_ecart;
    }

    public function creationsByDocumentsIds(array $documents_id, $document_type) {
        
    }

    public function execute() {
        $this->updates();
        $this->creations();
    }

    public function isInAlerte($document){
        
    }
    
    public function isInAlerteWithDRM($document,$drm) {
        if(!$document) return false;
        if(!$drm) return false;
        foreach ($document->declarations as $hashKey => $declaration) {
            $prod_node = $drm->getProduit(str_replace('-', '/',$hashKey));
            $stock_drm = 0;
            if($prod_node){
                $stock_drm = $prod_node->total_debut_mois;
            } 
            $diff = $stock_drm - $declaration->stock_declare;
            $seuil = $this->getConfig()->getOption('seuil');
            if (abs($diff) >  (abs($declaration->stock_declare) * ($seuil/100))){
                return true;
                
            }                
        }
        return false;
    }

    public function updatesByDocumentsIds(array $documents_id, $document_type) {
        
    }

     public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_ECART;
    }
}