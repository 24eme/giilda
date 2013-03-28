<?php

abstract class AlerteGenerationVrac extends AlerteGeneration {
    
    const TYPE_DOCUMENT = 'Vrac';

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->libelle_document = 'Contrat n° ' . VracClient::getInstance()->getNumeroArchiveEtDate(str_replace('VRAC-', '', $alerte->id_document));
    }

    protected function createOrFindByVrac($vrac) {
        $alerte = $this->createOrFind($vrac->_id);

        switch ($this->getTypeAlerte()) {
            case AlerteClient::VRAC_NON_SOLDES:
                $alerte->identifiant =  $vrac->vendeur_identifiant;
                break;
            default:
        $alerte->identifiant =  $vrac->acheteur_identifiant;
                break;
        }

        $alerte->campagne = $vrac->campagne;
        $alerte->region = $vrac->vendeur->region;
        $alerte->declarant_nom = $vrac->vendeur->nom;
        $alerte->type_document = $vrac->type;
        
        return $alerte;
    }

}