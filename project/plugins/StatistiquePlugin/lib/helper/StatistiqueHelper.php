<?php

function getTransmissionStatut($doc) {
    if (isset($doc['transmission_douane']) && $doc['transmission_douane']['coherente'] === true) {
        return 'Douane OK';
    }

    if (isset($doc['transmission_douane']) && $doc['transmission_douane']['success'] === true) {
        return 'Transmise';
    }

    if (isset($doc['valide']) && $doc['valide']['date_signee']) {
        return ($doc['teledeclare']) ? 'Télédéclarée' : 'Importée';
    }

    return 'En attente';
}