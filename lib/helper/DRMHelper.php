<?php

function mouvement_get_words($mouvements) {
    $words = array();
        
    foreach($mouvements as $mouvement) {
        $words[mouvement_get_id($mouvement)] = mouvement_get_word($mouvement);
    }

    return $words;
}

function mouvement_get_word($mouvement) {
    return array_merge(
        Search::getWords($mouvement->produit_libelle),
        Search::getWords($mouvement->type_libelle),
        Search::getWords($mouvement->detail_libelle),
        Search::getWords($mouvement->vrac_numero)
    );
}

function mouvement_get_id($mouvement) {

    return str_replace("/", '-', $mouvement->id);
}