<?php

function comptePictoCssClass($compte) {

        if($compte instanceof sfOutputEscaperArrayDecorator) {
            $compte = $compte->getRawValue();
        }

        $compteType = null;
        if(isset($compte['compte_type'])) {
            $compteType = $compte['compte_type'];
        }
        if(isset($compte->compte_type)) {
            $compteType = $compte->compte_type;
        }

        $hasTagEtablissement = false;
        
        $tagsAutomatique = null;
        if (isset($compte['tags']) && isset($compte['tags']['automatique'])) {
            $tagsAutomatique = (is_object($compte['tags']['automatique']))? $compte['tags']['automatique']->toArray(true, false) : $compte['tags']['automatique'];
        }

        if($compteType && $tagsAutomatique && in_array('etablissement', $tagsAutomatique)) {
            $hasTagEtablissement = true;
        }

        if($compteType == CompteClient::TYPE_COMPTE_ETABLISSEMENT || $compte instanceof Etablissement || $hasTagEtablissement){

            return "glyphicon glyphicon-home";
        }

        if($compteType == CompteClient::TYPE_COMPTE_SOCIETE || $compte instanceof Societe){

            return "glyphicon glyphicon-calendar";
        }

    return "glyphicon glyphicon-user";
}

function formatSIRET($siret, $offuscer = false) {
    if($offuscer) {

        return preg_replace('/^(\d\d\d)(\d\d\d)(\d\d\d).*/', '\1 XXX \3', $siret);
    }

  return preg_replace('/^(\d\d\d)(\d\d\d)(\d\d\d)/', '\1 \2 \3 ', $siret);
}
