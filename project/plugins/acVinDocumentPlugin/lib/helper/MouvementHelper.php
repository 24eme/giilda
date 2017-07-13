<?php

function mouvement_get_words($mouvementsByDrm) {
    $words = array();
    foreach($mouvementsByDrm as $mouvements) {
      foreach($mouvements as $mouvement) {
          $words[mouvement_get_id($mouvement)] = mouvement_get_word($mouvement);
      }
    }

    return $words;
}

function mouvement_get_word($mouvement) {
    $doc_id_libelle = DRMClient::getInstance()->getLibelleFromId($mouvement->doc_id);
    return array_merge(
        Search::getWords($mouvement->produit_libelle),
        Search::getWords($mouvement->type_libelle),
        Search::getWords($mouvement->detail_libelle),
        Search::getWords($mouvement->vrac_numero),
        Search::getWords($mouvement->vrac_destinataire),
	array(str_replace(' ', '_', $mouvement->produit_libelle)),
  array(str_replace(' ', '_', $doc_id_libelle))
    );
}

function mouvement_get_id($mouvement) {
    return str_replace("/", '-', $mouvement->id);
}

function mouvement_stock_id($stockid) {
    return str_replace("/", '-', $stockid);
}
