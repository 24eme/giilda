<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationDS
 * @author mathurin
 */
abstract class AlerteGenerationDS extends AlerteGeneration {

    const TYPE_DOCUMENT = 'DS';
    
    protected function createOrFindByDS($ds) {
        $alerte = $this->createOrFind($ds->_id);
        $alerte->identifiant = $ds->identifiant;
        $alerte->campagne = $ds->campagne;
        $alerte->region = $ds->declarant->region;
        $alerte->declarant_nom = $ds->declarant->nom;
        $alerte->type_document = $ds->type;
        return $alerte;
    }

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->libelle_document = DSClient::getInstance()->getLibelleFromId($alerte->id_document);
    }
   
}