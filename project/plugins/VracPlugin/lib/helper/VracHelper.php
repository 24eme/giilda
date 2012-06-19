<?php

function statusImg($status)
{
    $imgObj = new stdClass();
    $imgObj->alt = '';
    $imgObj->src = '/images/icons/';
    
    if(is_null($status)) return $imgObj;
    
    switch ($status)
    {
        case VracClient::STATUS_CONTRAT_ANNULE:
        {
            $imgObj->alt = 'annulé';
            $imgObj->src .= 'annule';
            return $imgObj;
        }
        case VracClient::STATUS_CONTRAT_SOLDE:            
        {
            $imgObj->alt = 'soldé';
            $imgObj->src .= 'solde';
            return $imgObj;
        }
        case VracClient::STATUS_CONTRAT_NONSOLDE:
        {
            $imgObj->alt = 'non soldé';
            $imgObj->src .= 'nonsolde';
            return $imgObj;
        }
        default :
            return $imgObj;
    }
}

function showRecapPrixUnitaire($vrac)
{
    if($type = $vrac->type_transaction)
    {
        switch ($type)
        {
            case 'raisins': return $vrac->prix_unitaire.' €/kg';
            case 'mouts': return $vrac->prix_unitaire.' €/hl';
            case 'vin_vrac': return $vrac->prix_unitaire.' €/hl';                   
            case 'vin_bouteille': 
                if ($vrac->bouteilles_quantite == 0 || $vrac->bouteilles_contenance == 0) {
                    return 0;
                }
                return $vrac->prix_unitaire.' €/btle, soit '.
                    $vrac->prix_total/($vrac->bouteilles_quantite*($vrac->bouteilles_contenance/10000)).' €/hl';
        }
    }    
    return '';
}

function showRecapVolume($vrac)
{
    if($type = $vrac->type_transaction)
    {
        switch ($type)
        {
            case 'raisins': return $vrac->raisin_quantite.' kg (raisins)';
            case 'mouts': return $vrac->jus_quantite.' hl (moûts)';
            case 'vin_vrac': return $vrac->jus_quantite.' hl (vin vrac)';                   
            case 'vin_bouteille': 
                return $vrac->bouteilles_quantite.
                    ' bouteilles, soit '.$vrac->bouteilles_quantite*($vrac->bouteilles_contenance/10000).' hl';
        }
    }    
    return '';
}

function showUnite($vrac)
{
    if($type = $vrac->type_transaction)
    {
        switch ($type)
        {
            case 'raisins': return 'kg';
            case 'mouts': return 'hl';
            case 'vin_vrac': return 'hl';                    
            case 'vin_bouteille': return 'btle';
        }
    }    
    return '';
}

      
function typeProduit($type)
{
    switch ($type) {
        case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE :
            return 'bouteilles';
        case VracClient::TYPE_TRANSACTION_VIN_VRAC :
            return 'vracs';
        case VracClient::TYPE_TRANSACTION_MOUTS :
            return 'moûts';
        case VracClient::TYPE_TRANSACTION_RAISINS :
            return 'raisins';
    }
    return '';
}   