<?php

function produit_get_words($produits) {
    $words = array();

    foreach ($produits as $produit) {
        $words[produit_get_id($produit)] = produit_get_word($produit);
    }

    return $words;
}

function produit_get_word($produit) {
    return array_merge(
            Search::getWords($produit->mois), Search::getWords($produit->produit_libelle), array(str_replace(' ', '_', $produit->produit_libelle))
    );
}

function produit_get_id($produit) {

    return 'produit_' . $produit->periode . str_replace('/', '-', $produit->produit_hash);
}

function getDrmTitle($drm) {
    $annee = substr($drm->periode, 0, 4);
    $mois = substr($drm->periode, 4, 2);
    $date = $annee . '-' . $mois . '-01';
    return 'DRM - ' . format_date($date, "MMMM", "fr_FR") . ' ' . $annee;
}

function getFrPeriodeElision($periode) {
    $annee = substr($periode, 0, 4);
    $mois = substr($periode, 4, 2);
    $date = $annee . '-' . $mois . '-01';
    return elision('de', ucfirst(format_date($date, "MMMM", "fr_FR"))) . ' ' . $annee;
}

function getNumberOfFirstProduitWithMovements($produits) {
    $cpt = 1;
    foreach ($produits as $produit) {
        if ($produit->hasMovements()) {
            return $cpt;
        }
        $cpt++;
    }
    return null;
}

function getClassGlobalEtatDRMCalendrier($isTeledeclarationMode, $calendrier, $periode, $etablissement = null) {
    $statut = $calendrier->getStatutForAllEtablissements($periode);
    if ($statut == DRMCalendrier::STATUT_NOUVELLE) {
        return 'nouv_campagne';
    }
    if ($isTeledeclarationMode) {
        if ($statut == DRMCalendrier::STATUT_VALIDEE) {
            return 'valide_campagne panel-success';
        }
        if ($statut == DRMCalendrier::STATUT_EN_COURS) {
            return 'attente_campagne';
        }
        return 'valide_campagne_non_teledeclaree';
    }

    //Cas VINSI
    $statut = $calendrier->getStatut($periode, $etablissement);

    if ($statut == DRMCalendrier::STATUT_VALIDEE_NON_TELEDECLARE) {
        return 'valide_campagne_teledeclaree panel-success ';
    }
    if ($statut == DRMCalendrier::STATUT_VALIDEE) {
        return 'valide_campagne_teledeclaree panel-success';
    }
    if (($statut == DRMCalendrier::STATUT_EN_COURS) || ($statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE)) {
        return 'attente_campagne_teledeclaree panel-primary';
    }
    if ($statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE) {
        return 'attente_campagne';
    }
    return 'valide_campagne panel-success';
}

function hasALink($isTeledeclarationMode, $calendrier, $periode, $etablissement = false) {
    $statut = $calendrier->getStatut($periode, $etablissement);
    if ($isTeledeclarationMode) {
        if (($statut == DRMCalendrier::STATUT_VALIDEE) || ($statut == DRMCalendrier::STATUT_EN_COURS)) {
            return $calendrier->isTeledeclare($periode, $etablissement);
        }
        return ($statut == DRMCalendrier::STATUT_NOUVELLE);
    }
    return true;
}

function getEtatDRMCalendrier($isTeledeclarationMode, $calendrier, $periode, $etablissement = false) {
    $statut = $calendrier->getStatut($periode, $etablissement);
    if ($statut == DRMCalendrier::STATUT_VALIDEE) {
        return 'Validée';
    }
    if ($statut == DRMCalendrier::STATUT_EN_COURS) {
        return 'En attente';
    }
    if ($statut == DRMCalendrier::STATUT_NOUVELLE) {
        return 'A créer';
    }
    if ($isTeledeclarationMode) {
        return 'Saisie interne';
    }
    if ($statut == DRMCalendrier::STATUT_VALIDEE_NON_TELEDECLARE) {
        return 'Validée';
    }
    if ($statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE) {
        return 'En attente';
    }
    return $statut;
}

function getTeledeclareeLabelCalendrier($isTeledeclarationMode, $calendrier, $periode, $etablissement = false) {
    if (isTeledeclareeCalendrier($isTeledeclarationMode, $calendrier, $periode))
        return  '(Télédéclarée)';
    else if ($isTeledeclarationMode)
        return '';
    else {
        $a = $calendrier->getNumeroArchive($periode, $etablissement);
        if ($a)
            return '('.$a.')' ;
    }
}

function isTeledeclareeCalendrier($isTeledeclarationMode, $calendrier, $periode, $etablissement = false) {
    $statut = $calendrier->getStatut($periode, $etablissement);
    if (!$isTeledeclarationMode) {
        if ((($statut == DRMCalendrier::STATUT_VALIDEE) || ($statut == DRMCalendrier::STATUT_EN_COURS)) && $calendrier->isTeledeclare($periode)) {

            return true;
        }
    }
    return false;
}

function getEtatDRMPictoCalendrier($isTeledeclaration, $calendrier, $periode, $etablissement = false) {
    $statut = $calendrier->getStatut($periode, $etablissement);
    if ($statut == DRMCalendrier::STATUT_EN_COURS) {
        return 'attente_etablissement';
    }
    if ($statut == DRMCalendrier::STATUT_NOUVELLE) {
        return 'nouv_etablissement';
    }
    if ($isTeledeclaration) {
        if ($statut == DRMCalendrier::STATUT_VALIDEE){
            return 'valide_etablissement';
        }
        return 'valide_papier_etablissement';
    }
    if ($statut == DRMCalendrier::STATUT_VALIDEE_NON_TELEDECLARE) {
            return 'valide_etablissement';
    }
    if ($statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE) {
        return 'attente_etablissement';
    }
    return 'valide_etablissement';
}

function getEtatDRMHrefCalendrier($isTeledeclaration,$calendrier, $periode, $etablissement = false) {
    $etablissementId = ($etablissement) ? $etablissement->identifiant : $calendrier->getIdentifiant();
    $statut = $calendrier->getStatut($periode, $etablissement);
    $periode_version = $calendrier->getPeriodeVersion($periode, $etablissement);
    if ($statut == DRMCalendrier::STATUT_VALIDEE || ($statut == DRMCalendrier::STATUT_VALIDEE_NON_TELEDECLARE && !$isTeledeclaration)) {
        return url_for('drm_visualisation', array('identifiant' => $etablissementId, 'periode_version' => $periode_version));
    }
    if (($statut == DRMCalendrier::STATUT_EN_COURS) || ($statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE)) {
        return url_for('drm_redirect_etape', array('identifiant' => $etablissementId, 'periode_version' => $periode_version));
    }
    if ($statut == DRMCalendrier::STATUT_NOUVELLE) {
        if($isTeledeclaration){
           return '#drm_nouvelle_'.$periode . '_' . $etablissementId;
        }else{

        return url_for('drm_nouvelle', array('identifiant' => $etablissementId, 'periode' => $periode));
        }
    }
    return "#";
}

function hasPopup($isTeledecaration, $calendrier, $periode, $etablissement = false) {
    if(!$isTeledecaration) return false;
    $statut = $calendrier->getStatut($periode, $etablissement);
    if ($statut == DRMCalendrier::STATUT_NOUVELLE) {
        return true;
    }
    return false;
}

function getEtatDRMLibelleCalendrier($calendrier, $periode, $etablissement = false) {
    $statut = $calendrier->getStatut($periode, $etablissement);
    if ($statut == DRMCalendrier::STATUT_VALIDEE) {
        return 'Voir la drm';
    }
    if ($statut == DRMCalendrier::STATUT_EN_COURS) {
        return 'Continuer';
    }
    if ($statut == DRMCalendrier::STATUT_VALIDEE_NON_TELEDECLARE) {
        return 'Voir la drm';
    }
    if ($statut == DRMCalendrier::STATUT_EN_COURS_NON_TELEDECLARE) {
        return 'Continuer';
    }
    if ($statut == DRMCalendrier::STATUT_NOUVELLE) {
        return 'A créer';
    }
    return $statut;
}

function getLibelleForGenre($genre) {
    if ($genre == 'TRANQ') {
        return 'TRANQUILLE';
    }
    return $genre;
}

function getLastDayForDrmPeriode($drm) {
    $dateFirst = new DateTime(substr($drm->periode, 0, 4) . '-' . substr($drm->periode, 4, 2) . '-01');
    return $dateFirst->format('t/m');
}
