<?php

function statusColor($status)
{
    
    if(is_null($status)) return '';
    
    switch ($status)
    {
        case VracClient::STATUS_CONTRAT_ANNULE:
            return 'red';
        case VracClient::STATUS_CONTRAT_SOLDE:
            return 'green';
        case VracClient::STATUS_CONTRAT_NONSOLDE:
            return 'yellow';
        default :
            return '';
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
            return 'Btl';
        case VracClient::TYPE_TRANSACTION_VIN_VRAC :
            return 'V';
        case VracClient::TYPE_TRANSACTION_MOUTS :
            return 'M';
        case VracClient::TYPE_TRANSACTION_RAISINS :
            return 'R';
    }
    return '';
}   