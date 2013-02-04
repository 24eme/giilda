<?php

abstract class AlerteGenerationVrac extends AlerteGeneration {

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->libelle_document = 'Contrat n° ' . VracClient::getInstance()->getNumeroArchiveEtDate(str_replace('VRAC-', '', $alerte->id_document));
    }

    protected function createOrFindByVrac($vrac) {
        $alerte = $this->createOrFind($vrac->_id);

        $alerte->identifiant = (isset($vrac->identifiant))? $vrac->identifiant : null;
        $alerte->campagne = $vrac->campagne;
        $alerte->region = $vrac->vendeur->region;
        $alerte->declarant_nom = $vrac->vendeur->nom;

        return $alerte;
    }

}