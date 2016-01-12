<?php

function comptePictoCssClass($compte) {
        
        $compteType = null;

        if(isset($compte['compte_type'])) {
            $compteType = $compte['compte_type'];
        }
        if(isset($compte->compte_type)) {
            $compteType = $compte->compte_type;
        }

        if($compteType == CompteClient::TYPE_COMPTE_ETABLISSEMENT || $compte instanceof Etablissement){
            
            return "glyphicon glyphicon-home";
        }

        if($compteType == CompteClient::TYPE_COMPTE_SOCIETE || $compte instanceof Societe){
            
            return "glyphicon glyphicon-calendar";
        }

    return "glyphicon glyphicon-user";
}