<?php
function getDateValidation($vrac){
    if(!$vrac->exist('valide') || !$vrac->valide->exist('date_saisie') || !$vrac->valide->date_saisie){
        return "Contrat non validé";
    }
    return $vrac->valide->date_saisie;
}

function getPrefixPoint($str,$size){
    if(strlen($str) > $size){
        return substr($str,0, strlen($str)-3).'...';
    }
    $res = '';
    $nb_char = ($str === null)? 0 : strlen($str);
    for ($i = 0; $i < $size - $nb_char; $i++) {
       $res.='.'; 
    }
    $res.=$str; 
    return $res;
}


function vracTypeExplication($vrac){
    switch ($vrac->type_transaction) {
            case VracClient::TYPE_TRANSACTION_VIN_VRAC :
                return "Le prix payé est exprimé en euros par hectolitre";
            case VracClient::TYPE_TRANSACTION_MOUTS :
                return "Le prix payé est exprimé en euros par hectolitre";
            case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE :
                return "Le prix payé est exprimé en euros pour une bouteille, un BIB® et sa contenance (comprenant le vin, la mise, les matières sèches ...)";
            case VracClient::TYPE_TRANSACTION_RAISINS :
                return "Le prix payé est exprimé en euros par kilogramme de raisin";
            default:
                return null;
        }    
}

function getMandataireVisa($vrac) {
    if(!$vrac->mandataire_exist){
        return "";
    }
    if(!$vrac->exist('signatures')){
        return "";
    }
    if(!$vrac->signatures->exist('mandataire')){
        return "";
    }
    return $vrac->signatures->mandataire->visa;
}

function getDateSignatureVendeur($vrac) {
    if(!$vrac->exist('signatures')){
        return "";
    }
    if(!$vrac->signatures->exist('vendeur')){
        return "";
    }
    return $vrac->signatures->vendeur->date_signature;
}

function getDateSignatureAcheteur($vrac) {
    if(!$vrac->exist('signatures')){
        return "";
    }
    if(!$vrac->signatures->exist('acheteur')){
        return "";
    }
    return $vrac->signatures->acheteur->date_signature;
}

function getPrixTouteLettre($vrac){
    $str_price = "".$vrac->prix_initial_total;
    if(preg_match('/^([0-9]*)(\.?[0-9]*)$/', $str_price, $matches)){
        $price = '~';
        if(!is_null($price_rounded = $matches[1])){
            if(intval($price_rounded) > 999999){
                $price_rounded_supmillion = substr($price_rounded,0, -6);
                $price.="\\numberstringnum{".$price_rounded_supmillion."}~million";
                if($price_rounded_supmillion > 1){
                   $price.="s"; 
                }
                $price_rounded_supmill = substr($price_rounded,0, -3);
                $price_rounded_infmill = substr($price_rounded, -3);
                $price.="\\numberstringnum{".$price_rounded_supmill."}~mille~\\numberstringnum{".$price_rounded_infmill."}~euro";
            }elseif(intval($price_rounded) > 9999){
                $price_rounded_supmill = substr($price_rounded,0, -3);
                $price_rounded_infmill = substr($price_rounded, -3);
                $price.="\\numberstringnum{".$price_rounded_supmill."}~mille~\\numberstringnum{".$price_rounded_infmill."}~euro";
            }else{
                $price.="\\numberstringnum{".$price_rounded."}~euro";
            }
            if($price_rounded > 0){
                $price.="s";
            }
        }
        if(!is_null($price_rounded_cents = $matches[2]) && $price_rounded_cents!="" && $price_rounded_cents > 0){
            $price.="~et~\\numberstringnum{".substr($price_rounded_cents,1)."}~ centimes";
        }
        return $price."~";
    }
    return "~0 euro~";
}

function getCheckBoxe($b) {
    return ($b)? '\squareChecked' : '$\square$';
}