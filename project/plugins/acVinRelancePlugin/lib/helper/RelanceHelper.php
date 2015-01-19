<?php


function printRelanceFormule($relance){
    echo 'Nous vous remercions de bien vouloir nous contacter afin de nous informer de la raison de cet écart.';
}

//function escape_string_for_latex($string) {
//    $disp = str_replace("&#039;", "'", $string);
//    $disp = str_replace("&amp;", "&", $disp);
//    $disp = str_replace("&", "\&", $disp);
//    $disp = str_replace("%", "\%", $disp);
//    return $disp;
//}

function getTableRowHead($rowHead){
    $tab = explode('|', escape_string_for_latex($rowHead));
    $res = '';
    foreach ($tab as $key => $h){
         $s = str_replace(';', '', $h);
         $res .= '\textbf{\small{'.$s.'}}';
         if($key< count($tab)-1)  $res.= " & ";
    }
    return $res;
}

function getTableLigne($row){
    $tab = explode('|', escape_string_for_latex($row));
    $res = '';
    foreach ($tab as $key => $l){
         $s = str_replace(';', '', $l);
         $res .= '\small{'.$s.'}';
         if($key< count($tab)-1)  $res .= " & ";
    }
    return $res;
}

function getLigne($row){
    $tab = explode('|', escape_string_for_latex($row));
    $res = '';
    foreach ($tab as $key => $l){
         $s = str_replace(';', '', $l);
         $res .= $s.'~';
    }
    return $res;
}

function getTableFormatVerification($verification){
    $cptX = substr_count($verification->liste_champs,'|')+1;
    $res = '';
    while($cptX){
        $res.='X';
        $cptX--;
    }
    return $res;
}

function echoTypeRelance($type){
    switch ($type) {
        case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE:
            echo 'DRM Manquante';
            break;
        case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE:
            echo 'DRA Manquante';
            break;

        default:
            break;
    }
}

function echoIntroRelance($type){
   switch ($type) {
        case RelanceClient::TYPE_RELANCE_DRM_MANQUANTE:
            echo 'Après étude de votre compte, sauf erreur de notre part, il apparaît que nous sommes en attente des DRM suivantes :';
            break;
        case RelanceClient::TYPE_RELANCE_DRA_MANQUANTE:
            echo 'Après étude de votre compte, sauf erreur de notre part, nous constatons des écarts de volumes entre différents éléments et nous souhaiterions pouvoir les rectifier :';
            break;

        default:
            break;
    } 
}

function getRegion($region){
    switch ($region) {
    case EtablissementClient::REGION_NANTES :
        return 'Vertou,';
    case EtablissementClient::REGION_HORSINTERLOIRE :
    case EtablissementClient::REGION_ANGERS :
    case EtablissementClient::REGION_TOURS :
    default:
        return 'Tours,';
    }
 }

 function getServicesOperateurs($relance){
     $result = '';
     foreach ($relance->emetteur->services_operateurs as $key => $operateur) {
         $result.= $operateur->nom.' - '.$operateur->telephone;
         if($operateur->email && $operateur->email != "") $result.= ' - '.$operateur->email;
         if($key< count($relance->emetteur->services_operateurs)-1)  $result.= " \\\\ ";
     }
     return $result;
 }