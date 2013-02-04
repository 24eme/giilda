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

    protected function createOrFindByDRM($drm) {
        $alerte = $this->createOrFind($drm->_id);

        $alerte->identifiant = $drm->identifiant;
        $alerte->campagne = $drm->campagne;
        $alerte->region = $drm->declarant->region;
        $alerte->declarant_nom = $drm->declarant->nom;

        return $alerte;
    }

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->datas_relances = null;
    }
}