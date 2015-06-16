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

function getClassEtatDRMCalendrier($calendrier, $periode) {
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_VALIDEE) {
        return 'valide_campagne';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_EN_COURS) {
        return 'attente_campagne';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_NOUVELLE) {
        return 'nouv_campagne';
    }
    return $calendrier->getStatut($periode);
}

function getEtatDRMCalendrier($calendrier, $periode) {
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_VALIDEE) {
        return 'Validée';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_EN_COURS) {
        return 'En attente';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_NOUVELLE) {
        return 'Nouvelle';
    }
    return $calendrier->getStatut($periode);
}

function getEtatDRMPictoCalendrier($calendrier, $periode) {
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_VALIDEE) {
        return 'valide_etablissement';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_EN_COURS) {
        return 'attente_etablissement';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_NOUVELLE) {
        return 'nouv_etablissement';
    }
    return $calendrier->getStatut($periode);
}


function getEtatDRMHrefCalendrier($calendrier, $periode) {
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_VALIDEE) {
        return url_for('drm_visualisation', array('identifiant' => $calendrier->getIdentifiant(), 'periode_version' => $calendrier->getPeriodeVersion($periode)));
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_EN_COURS) {
        return url_for('drm_redirect_etape', array('identifiant' => $calendrier->getIdentifiant(), 'periode_version' => $calendrier->getPeriodeVersion($periode)));
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_NOUVELLE) {
        return url_for('drm_nouvelle', array('identifiant' => $calendrier->getIdentifiant(), 'periode' => $periode));
    }
    return $calendrier->getStatut($periode);
}

function getEtatDRMLibelleCalendrier($calendrier, $periode) {
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_VALIDEE) {
        return 'Voir la drm';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_EN_COURS) {
        return 'En attente';
    }
    if ($calendrier->getStatut($periode) == DRMCalendrier::STATUT_NOUVELLE) {
        return 'Nouvelle';
    }
    return $calendrier->getStatut($periode);
}