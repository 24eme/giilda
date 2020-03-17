<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
abstract class AlerteGenerationDRM extends AlerteGeneration {

    const TYPE_DOCUMENT = 'DRM';
    
    protected function createOrFindByDRM($drm) {        
        $alerte = $this->createOrFind(DRMClient::getInstance()->buildId($drm->identifiant, $drm->periode));
        
        $alerte->identifiant = $drm->identifiant;
        $alerte->campagne = $drm->campagne;
        $alerte->region = $drm->declarant->region;
        $alerte->declarant_nom = $drm->declarant->nom;
        $alerte->type_document = isset($drm->type) ? $drm->type : self::TYPE_DOCUMENT;
        return $alerte;
    }

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->libelle_document = DRMClient::getInstance()->getLibelleFromId($alerte->id_document);
    }
    
    protected function getEtablissementsByTypeDR($type_dr) {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-inter-loire', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_PRODUCTEUR), null, -1);
        $etablissements = array();
        foreach ($etablissement_rows as $etablissement_row) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);

            if ($etablissement->type_dr != $type_dr) {
                continue;
            }
            if (($type_dr == EtablissementClient::TYPE_DR_DRM)
                    && ($etablissement->exclusion_drm == EtablissementClient::EXCLUSION_DRM_OUI)) {
                continue;
            }
            $etablissements[] = $etablissement;
        }
        return $etablissements;
    }
   
}