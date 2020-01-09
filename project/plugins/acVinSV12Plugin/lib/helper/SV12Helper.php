<?php

function contrat_get_words($contrats) {
    $words = array();
        
    foreach($contrats as $contrat) {
        $words[contrat_get_id($contrat)] = contrat_get_word($contrat);
    }

    return $words;
}

function contrat_get_word($contrat) {
    if ($contrat->exist('commentaire')) {
        return array_merge(
            Search::getWords($contrat->produit_libelle),
            Search::getWords($contrat->vendeur_nom),
            Search::getWords($contrat->vendeur_identifiant),
            Search::getWords($contrat->contrat_numero),
            Search::getWords($contrat->commentaire),
            Search::getWords($contrat->contrat_type)
        );
    }
    return array_merge(
        Search::getWords($contrat->produit_libelle),
        Search::getWords($contrat->vendeur_nom),
        Search::getWords($contrat->vendeur_identifiant),
        Search::getWords($contrat->contrat_numero),
        Search::getWords($contrat->contrat_type)
    );
}

function contrat_get_id($contrat) {
    if ($contrat->contrat_numero) {
        return 'contrat_'.$contrat->contrat_numero;
    }
    return 'contrat_'.md5($contrat->contrat_numero.$contrat->vendeur_nom.$contrat->produit_libelle.$contrat->contrat_type);
}
