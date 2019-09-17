<?php

function revendication_get_words($revendications) {
    $words = array();

    foreach($revendications as $revendication) {
        $words[revendication_get_id($revendication)] = revendication_get_word($revendication);
    }
    return $words;
}

function revendication_get_word($revendication) {
    return array_merge(
        Search::getWords($revendication->odg),
        Search::getWords($revendication->num_certif),
        Search::getWords($revendication->declarant_cvi),
        Search::getWords($revendication->declarant_nom),
        Search::getWords($revendication->produit_libelle),
        Search::getWords($revendication->volume)
    );
}

function revendication_get_id($revendication) {

    return $revendication->etablissement_identifiant."_".str_replace(" ",'',$revendication->code_douane)."_".$revendication->ligne_identifiant;

}
