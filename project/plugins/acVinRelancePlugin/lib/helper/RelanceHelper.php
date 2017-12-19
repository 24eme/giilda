<?php

function printRelanceFormule($relance) {
    echo 'Nous vous remercions de bien vouloir nous contacter afin de nous informer de la raison de cet écart.';
}

//function escape_string_for_latex($string) {
//    $disp = str_replace("&#039;", "'", $string);
//    $disp = str_replace("&amp;", "&", $disp);
//    $disp = str_replace("&", "\&", $disp);
//    $disp = str_replace("%", "\%", $disp);
//    return $disp;
//}

function getTableRowHead($rowHead) {
    $tab = explode('|', escape_string_for_latex($rowHead));
    $res = '';
    foreach ($tab as $key => $h) {
        $s = str_replace(';', '', $h);
        $res .= '\textbf{\small{' . $s . '}}';
        if ($key < count($tab) - 1)
            $res.= " & ";
    }
    return $res;
}

function getTableLigne($row) {
    $tab = explode('|', escape_string_for_latex($row));
    $res = '';
    foreach ($tab as $key => $l) {
        $s = str_replace(';', '', $l);
        $res .= '\small{' . $s . '}';
        if ($key < count($tab) - 1)
            $res .= " & ";
    }
    return $res;
}

function getLigne($row) {
    $tab = explode('|', escape_string_for_latex($row));
    $res = '';
    foreach ($tab as $key => $l) {
        $s = str_replace(';', '', $l);
        $res .= $s . '~';
    }
    return $res;
}

function getTableFormatVerification($verification) {
    $cptX = substr_count($verification->liste_champs, '|') + 1;
    $res = '';
    while ($cptX) {
        $res.='X';
        $cptX--;
    }
    return $res;
}

function echoTypeRelance($type) {
    switch ($type) {
        case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE:
            echo 'DRM Manquante';
            break;
        case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE:
            echo 'DRA Manquante';
            break;
        case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE_AR:
            echo 'DRM Manquante avec AR';
            break;
        case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE_AR:
            echo 'DRA Manquante avec AR';
            break;
        default:
            break;
    }
}

function echoIntroRelance($type, $ar = false) {
    if (!$ar) {
        switch ($type) {
            case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE:
                echo 'Dans le cadre de nos vérifications, sauf erreur de notre part, la procédure de transmission des DRM mise en place avec l’administration des Douanes ne nous a pas permis de recevoir la Déclaration Récapitulative Mensuelle des mois suivants :';
                break;
            case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE_AR:
                echo 'Nous constatons, malgré notre précédente relance, que nous sommes toujours en attente la Déclaration Récapitulative Mensuelle des mois suivants :';
                break;
            case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE:
                echo 'Dans le cadre de nos vérifications, sauf erreur de notre part, la procédure de transmission de DRA mise en place avec l’administration des Douanes ne nous a pas permis de recevoir la Déclaration Récapitulative Annuelle des campagnes suivantes :';
                break;
case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE_AR:
                echo 'Nous constatons, malgré notre précédente relance, que nous sommes toujours en attente de la Déclaration Récapitulative Annuelle suivante :';
                break;
            default:
                break;
        }
    }
}

function getRegion($region) {
    return 'Tours,';
}

function printContact($relance) {
    $result = "";
    foreach ($relance->emetteur->services_operateurs as $operateur) {
        $result.=$operateur->nom . ' - ' . $operateur->telephone . '\\\\';
        $result.=$operateur->email . '\\\\';
    }
    echo $result;
}

function getServicesOperateurs($relance) {
    return $relance->responsable_economique . "\\\\Responsable Economie et Etudes";
}

function printRappelLoi($type, $ar = false) {

    if (!$ar) {
        switch ($type) {
            case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE:
            case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE:
                echo 'Nous vous remercions de bien vouloir nous faire parvenir une copie de ces documents dans les meilleurs délais. \\\\\\\\Ces éléments sont indispensables à l’Interprofession pour assurer le suivi des données statistiques des appellations. Ils permettent également de déterminer l’assiette d’évaluation des cotisations interprofessionnelles (cf Article II-3 Connaissance des sorties de chais de l’Accord interprofessionnel en vigueur). \\\\Nous restons à votre disposition pour toute information complémentaire que vous jugeriez nécessaire.\\\\\\\\Dans cette attente, nous vous prions d’agréer, Madame, Monsieur, l’expression de nos salutations distinguées.';
                break;
            case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE_AR:
            case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE_AR:
                echo 'Nous vous demandons de bien vouloir nous faire parvenir une copie de ces documents \\underline{\\textbf{sous un mois.}}\\\\\\\\A défaut, InterLoire sera dans l’obligation de procéder à une évaluation d’office du montant des cotisations dues  conformément à l’article~VI-4.2 de l’Accord interprofessionnel en vigueur.\\\\\\\\Nous restons à votre disposition pour toute information complémentaire que vous jugeriez nécessaire.\\\\\\\\Dans cette attente, nous vous prions d’agréer, Madame, Monsieur, l’expression de nos salutations distinguées.';
                break;

            default:
                break;
        }
    }
}
