<?php

function getCheckBoxe($b) {
    return ($b) ? '\squareChecked' : '$\square$';
}

function getActualRegime($regimes_array, $regime_crd) {
    foreach ($regimes_array as $regime_crd_key => $long_libelle) {
        if ($regime_crd_key == $regime_crd && str_contains($long_libelle, '+')) {
            $split_values = explode('+', $long_libelle);

            $key_value_pairs = array_map(function($split) use ($regimes_array) {
                return [in_array($split, $regimes_array) => $split];
            }, $split_values);

            $detect_regime_array = array_combine(array_keys($key_value_pairs), array_column($key_value_pairs, 0));
            foreach ($detect_regime_array as $index => $value) {
                $detect_regime_array[$index] = trim($value);
            }

        }
        elseif ($regime_crd_key == $regime_crd) {
            $detect_regime_array[in_array($long_libelle, $regimes_array)] = $long_libelle;
        }
    }
    $ret = array(EtablissementClient::REGIME_CRD_PERSONNALISE, EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE, EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU);
    foreach ($ret as $index => $libelle) {
        $libelle_corresp = EtablissementClient::$regimes_crds_libelles[$libelle];
        foreach ($detect_regime_array as $key => $regime) {
            if (strcmp($libelle_corresp, $regime) == 0) {
                $ret[$index] = [$libelle_corresp => true];
                break;
            } else {
                $ret[$index] = [$libelle_corresp => false];
            }
        }
    }

//reussir a changer la valeur des key ou alors ajouter des valeurs en + (true/false) a chq libelle
    return $ret;
}

function getInfosInterpro() {
    return sfConfig::get('app_teledeclaration_contact_drm');
}

function getAdresseInterpro() {
    return sfConfig::get('app_teledeclaration_adresse_interpro');
}

function getDrmContact($drm) {
    $infosInterloire = getInfosInterpro();
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
    if(!$float){
        return '';
    }

    $float = FloatHelper::getInstance()->format($float);

    // credits: https://stackoverflow.com/a/2966878
    $rounded = intval(round($float * 100)) / 100.0;

    if ($rounded == $float) {
        $format = "%.0".FloatHelper::getInstance()->getDefaultDecimalFormat()."f";
    } else {
        $format = "%.0".FloatHelper::getInstance()->getMaxDecimalAuthorized()."f";
    }

    echo sprintf($format, $float) . ' hl';
}


function getArialNumber($number) {
    return number_format($number, 0, '.', ' ');
}


function sprintDroitDouane($float) {
    if (is_null($float))
        $float = 0;
    return round($float);
}
