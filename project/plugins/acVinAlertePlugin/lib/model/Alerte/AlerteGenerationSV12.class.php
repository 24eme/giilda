<?php

abstract class AlerteGenerationSV12 extends AlerteGeneration {

    protected function storeDatasRelance(Alerte $alerte) {
        $alerte->libelle_document = SV12Client::getInstance()->getLibelleFromId($alerte->id_document);
    }

    protected function createOrFindBySV12($sv12) {
        $alerte = $this->createOrFind($sv12->_id);

        $alerte->identifiant = $sv12->identifiant;
        $alerte->campagne = $sv12->campagne;
        $alerte->region = $sv12->declarant->region;
        $alerte->declarant_nom = $sv12->declarant->nom;

        return $alerte;
    }

}