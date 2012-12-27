<?php

function getCvoLabels($label)
{
   $cvo_nature = array(VracClient::CVO_NATURE_MARCHE_DEFINITIF => 'Marché définitif',
                       VracClient::CVO_NATURE_COMPENSATION => 'Compensation',
                       VracClient::CVO_NATURE_NON_FINANCIERE => 'Non financière',
                       VracClient::CVO_NATURE_VINAIGRERIE => 'Vinaigrerie');
   return $cvo_nature[$label];
}

function dateCampagneViticolePresent()
{
    $date = date('mY');
    $mois = substr($date, 0,2);
    $annee = substr($date, 2,6);
    $campagne = ($mois<8)? ($annee-1).'/'.$annee : $annee.'/'.($annee+1);
    return $campagne;
}

function dateCampagneViticole($date)
{
    $date_exploded = explode("/", $date);
    $mois = $date_exploded[1];
    $annee = $date_exploded[2];
    $campagne = ($mois<8)? ($annee-1).'/'.$annee : $annee.'/'.($annee+1);
    return $campagne;
}
 
function isARechercheParam($actifs,$label)
{
    return in_array($label, $actifs);
}

function statusColor($status)
{
    
    if(is_null($status)) return '';
    
    switch ($status)
    {
        case VracClient::STATUS_CONTRAT_ANNULE:
            return 'statut_annule';
        case VracClient::STATUS_CONTRAT_SOLDE:
            return 'statut_solde';
        case VracClient::STATUS_CONTRAT_NONSOLDE:
            return 'statut_non-solde';
        default :
            return '';
    }
}

function showRecapPrixUnitaire($vrac)
{
    $unite = showPrixUnitaireUnite($vrac);

    if($vrac->hasPrixVariable() && !$vrac->hasPrixDefinitif()) {
        return sprintf("%s (Prix non définitif)", showRecapPrixUnitaireByUniteAndPrix($unite, $vrac->prix_unitaire, $vrac->prix_unitaire_hl));
    } elseif($vrac->hasPrixVariable() && $vrac->hasPrixDefinitif()) {
        return sprintf("%s (Prix initial : %s)", 
                        showRecapPrixUnitaireByUniteAndPrix($unite, $vrac->prix_unitaire, $vrac->prix_unitaire_hl), 
                        showRecapPrixUnitaireByUniteAndPrix($unite, $vrac->prix_initial_unitaire, $vrac->prix_initial_unitaire_hl));
    }

    return showRecapPrixUnitaireByUniteAndPrix($unite, $vrac->prix_unitaire, $vrac->prix_unitaire_hl);
}

function showRecapPrixUnitaireByUniteAndPrix($unite, $prix_unitaire, $prix_unitaire_hl)
{   
    if($unite == '€/hl') {
        return sprintf('%s €/hl', echoF($prix_unitaire));
    }

    return sprintf('%s %s, soit %s €/hl', echoF($prix_unitaire), $unite, echoF($prix_unitaire_hl));
}

function showPrixUnitaireUnite($vrac) {
    switch ($vrac->type_transaction)
    {
        case VracClient::TYPE_TRANSACTION_RAISINS: 
            return '€/kg';
            break;
        case VracClient::TYPE_TRANSACTION_MOUTS: 
            return '€/hl';
            break;
        case VracClient::TYPE_TRANSACTION_VIN_VRAC: 
            return '€/hl';  
            break;                 
        case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE: 
            return '€/btle';
            break;
    }
}

function showRecapPrixTotal($vrac)
{
    if($vrac->hasPrixVariable() && !$vrac->hasPrixDefinitif()) {
        
        return sprintf('%s € (Prix non définitif)', echoF($vrac->getPrixTotal()));
    }

    if($vrac->hasPrixVariable() && $vrac->hasPrixDefinitif()) {

        return sprintf('%s € (Prix initial : %s €)', echoF($vrac->getPrixTotal()), echoF($vrac->getPrixInitialTotal()));
    }

    return sprintf('%s €', echoF($vrac->getPrixTotal()));
}

function showType($vrac)
{
    if($type = $vrac->type_transaction)
    {
        return showTypeFromLabel($type);
    }    
    return '';
}

function showTypeFromLabel($type)
{
    switch ($type)
        {
            case VracClient::TYPE_TRANSACTION_VIN_VRAC: return 'Vrac';                   
            case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE: return 'Conditionné';
            default: return $type;
        }
}

function showRecapVolumePropose($vrac)
{
    if($type = $vrac->type_transaction)
    {
        switch ($type)
        {
            case VracClient::TYPE_TRANSACTION_RAISINS: 
                return echoF($vrac->raisin_quantite).' kg (raisins), soit '.echoF($vrac->volume_propose).' hl';
            case VracClient::TYPE_TRANSACTION_MOUTS: return echoF($vrac->volume_propose).' hl (moûts)';
            case VracClient::TYPE_TRANSACTION_VIN_VRAC: return echoF($vrac->volume_propose).' hl (vrac)';                   
            case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE: 
                return echoF($vrac->bouteilles_quantite).
                    ' bouteilles ('.$vrac->bouteilles_contenance_libelle.'), soit '.echoF($vrac->volume_propose).' hl' ;
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
            case VracClient::TYPE_TRANSACTION_RAISINS: return 'kg';
            case VracClient::TYPE_TRANSACTION_MOUTS: return 'hl';
            case VracClient::TYPE_TRANSACTION_VIN_VRAC: return 'hl';                    
            case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE: return 'btle';
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

function echoF($f){
    return sprintf("%01.02f", round($f, 2)); 
}

function vrac_get_words($vracs) {
    $words = array();
        
    foreach($vracs as $vrac) {
        $words[vrac_get_id($vrac)] = vrac_get_word($vrac);
    }

    return $words;
}

function vrac_get_word($vrac) {

    return array_merge(
        Search::getWords($vrac->value[VracClient::VRAC_VIEW_NUMARCHIVE]),
        Search::getWords($vrac->value[VracClient::VRAC_VIEW_VENDEUR_NOM]),
        Search::getWords($vrac->value[VracClient::VRAC_VIEW_ACHETEUR_NOM]),
        Search::getWords($vrac->value[VracClient::VRAC_VIEW_MANDATAIRE_NOM]),
        Search::getWords($vrac->value[VracClient::VRAC_VIEW_MANDATAIRE_NOM]),
        Search::getWords(ConfigurationClient::getCurrent()->get($vrac->value[VracClient::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat())
    );
}

function vrac_get_id($vrac) {

    return 'vrac_'.$vrac->value[VracClient::VRAC_VIEW_NUMCONTRAT];
}
