<?php

abstract class AlerteGenerationVrac extends AlerteGeneration {

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->datas_relances = 'Contrat du ' . VracClient::getInstance()->getLibelleContratNum(str_replace('VRAC-', '', $alerte->id_document));
    }

    protected function createOrFindByVrac($vrac) {
        $alerte = $this->createOrFind($vrac->_id);

        $alerte->identifiant = $vrac->identifiant;
        $alerte->campagne = $vrac->campagne;
        $alerte->region = $vrac->vendeur->region;
        $alerte->declarant_nom = $vrac->vendeur->nom;

        return $alerte;
    }

}