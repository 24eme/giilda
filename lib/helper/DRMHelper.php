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
    foreach ($produits as $produit){
        if($produit->hasMovements()){
            return $cpt;
        }
        $cpt++;
    }
    return null;
}
