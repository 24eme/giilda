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
        $alerte->type_document = $drm->type;
        return $alerte;
    }

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->libelle_document = DRMClient::getInstance()->getLibelleFromId($alerte->id_document);
    }
   
}