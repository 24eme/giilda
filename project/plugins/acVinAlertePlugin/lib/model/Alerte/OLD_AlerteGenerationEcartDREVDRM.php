<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationEcartDREVDRM
 * @author mathurin
 */
class AlerteGenerationEcartDREVDRM extends AlerteGenerationDRM {

    public function getTypeAlerte() {
        return AlerteClient::ECART_DREV_DRM;
    }

    public function creations() {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-declaration', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_PRODUCTEUR));
        $campagnes = $this->getCampagnes();
        foreach ($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);
            foreach ($campagnes as $campagne) {
                $drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($etablissement->identifiant, $campagne);
                if(!$drm) continue;
                $drm_master = $drm->findMaster();
                $drev = RevendicationClient::getInstance()->findByOdgAndCampagne($etablissement->region,$campagne);
                if(!$drev) continue;
                if($this->isInAlerteWithDrev($drm_master,$drev)){
                    $alerte = $this->createOrFindByDRM($this->buildEcartDRMDrev($etablissement, $drm_master));
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
            $drm = DRMClient::getInstance()->find($id_document);
            if(!$drm) continue;
            $drm_master = $drm->findMaster();
            $drev = RevendicationClient::getInstance()->findByOdgAndCampagne($drm_master->declarant->region, $drm_master->campagne);
            if(!$drev) continue;
            if ($this->isInAlerteWithDrev($drm_master,$drev)) {
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

    protected function buildEcartDRMDrev($etablissement, $drm) {
        
        $drm_ecart = new stdClass();

        $drm_ecart->identifiant = $etablissement->identifiant;
        $drm_ecart->periode = $drm->periode;
        $drm_ecart->campagne = $drm->campagne;
        $drm_ecart->version = $drm->version;
        $drm_ecart->declarant = new stdClass();
        $drm_ecart->declarant->region = $etablissement->region;
        $drm_ecart->declarant->nom = $etablissement->nom;
        $drm_ecart->_id = $drm->_id;

        return $drm_ecart;
    }

    public function creationsByDocumentsIds(array $documents_id, $document_type) {
        
    }

    public function execute() {
        $this->updates();
        $this->creations();
    }

    public function isInAlerteWithDrev($document,$drev) {
        if(!$document) return false;
        if(!$drev) return false;
        if($drev->exist('datas') && $drev->datas->exist($document->identifiant)){
            foreach ($drev->datas->{$document->identifiant}->produits as $produit) {
                $prod_node = $document->getProduit($produit->produit_hash);
                $stock_drm = 0;
                if($prod_node){
                    $stock_drm = $prod_node->entrees->recolte;
                }
                $rev_vol = 0;
                foreach ($produit->volumes as $num_ca => $vol) {
                    $rev_vol+=$vol->volume;
                }
                $diff = $stock_drm - $rev_vol;
                $seuil = $this->getConfig()->getOption('seuil');

                if (abs($diff) >  (abs($rev_vol) * ($seuil/100))){
                    return true;
                    
                }                
            }
            
        }
        return false;
    }

    public function updatesByDocumentsIds(array $documents_id, $document_type) {
        
    }

    public function isInAlerte($document) {
        
    }
    
    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_ECART;
    }

}