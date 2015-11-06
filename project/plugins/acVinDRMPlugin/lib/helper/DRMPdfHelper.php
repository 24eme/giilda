<?php

function getCheckBoxe($b) {
    return ($b) ? '\squareChecked' : '$\square$';
}

function getInfosInterloire() {
    return sfConfig::get('app_teledeclaration_contact_drm');
}

function getAdresseInterloire() {
    return sfConfig::get('app_teledeclaration_adresse_interloire');
}

function getDrmContact($drm) {
    $infosInterloire = getInfosInterloire();
    $infosInterloireRegion = $infosInterloire[$drm->region];
    $drmContact = "Votre contact : " . $infosInterloireRegion['nom'] . ' - Tél: ' . $infosInterloireRegion['telephone'];
    $drmContact .= '\\\\ Email : ' . $infosInterloireRegion['email'];
    return $drmContact;
}

function getDrmSocieteAdresse($drm) {
    return $drm->societe->adresse . ' ' . $drm->societe->code_postal . ' ' . $drm->societe->commune;
}

function getDrmEtablissementAdresse($drm) {
    return $drm->declarant->adresse . ' ' . $drm->declarant->code_postal . ' ' . $drm->declarant->commune;
}

function sprintFloat($float, $format = "%01.02f") {
    if (is_null($float))
        $float = 0.00;
    return sprintf($format, $float);
}

function echoFloatWithHl($float) {    
    echo ($float)? sprintFloat($float).' hl' : '';
}


function getArialNumber($number) {
    return number_format($number, 0, '.', ' ');
}


function sprintDroitDouane($float) {
    if (is_null($float))
        $float = 0;
    return round($float);
}