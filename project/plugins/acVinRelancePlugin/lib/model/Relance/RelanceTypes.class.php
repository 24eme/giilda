<?php
/**
 * Model for RelanceTypes
 *
 */

class RelanceTypes extends BaseRelanceTypes {

    public function storeDescriptionsForType($type,$campagne =null,$idDoc = null) {
    
        sfApplicationConfiguration::getActive()->loadHelpers('Date');
    switch ($type) {
        case AlerteClient::DRM_MANQUANTE:            
             $this->multiple = false;
            $this->titre = "DRM manquante(s)";
            $this->refarticle = "cf Art III-3 et VI-3 Accord interprofessionnel en vigueur";
            $this->description = "Nous vous remercions de nous transmettre votre DRM du (ou des) mois suivant(s) :";
            break;
        case AlerteClient::DRA_MANQUANTE:            
             $this->multiple = false;
            $this->titre = "DRA manquante(s)";
            $this->refarticle = "cf Art III-3 et VI-3 Accord interprofessionnel en vigueur";
            $this->description = "Nous vous remercions de nous transmettre votre DRA du mois de :";
            break; 
//        case AlerteClient::VRAC_NON_SOLDES :
//            $this->titre = "Contrats non soldés";
//            $this->refarticle = "cf Art III-3 et VI-3 Accord interprofessionnel en vigueur";
//            $this->multiple = true;
//            $this->liste_champs = "N° d'Enregistrement|Date signature|Coordonnées négoce|Volume proposé|Volume enlevé|Commentaires";
//            $this->description = "Le(s) contrat(s) d’achats enregistré(s) selon les références ci-après ne sont pas soldés :";
//            $this->description_fin = "Si dans vos comptes, ce(s) contrat(s) apparaissent comme soldé(s), nous vous remercions de bien vouloir nous informer des enlèvements correspondants ainsi que les mois des DRM correspondantes. 
//\\\\ \\\\ En revanche, si ces enlèvements n’ont été portés sur aucune DRM, nous vous remercions de bien vouloir effectuer la rectification dans les meilleurs délais.
// \\\\ \\\\ Bien évidemment, en cas de contrat(s) effectivement non soldé(s), aucun élément n’est à fournir.";
//            break;
//        case AlerteClient::VRAC_ATTENTE_ORIGINAL :
//            $this->titre = "Contrats en attente de l’original";
//            $this->refarticle = "cf Art III-1 Accord interprofessionnel en vigueur";
//            $this->multiple = true;
//            $this->liste_champs = "N° enregistrement|Date signature|Coordonnées viticulteur|Volume proposé";
//            $this->description = "Nous sommes en attente de réception du document original concernant le(s) contrat(s) référencé(s) ci-après :";
//            $this->description_fin = "";
//            break;
//        case AlerteClient::VRAC_PRIX_DEFINITIFS :
//            $this->titre = "Contrats à prix définitifs non déterminés";
//            $this->refarticle = "cf Art III-2 Accord interprofessionnel en vigueur";
//            $this->multiple = true;
//            $this->liste_champs = "N° enregistrement|Date signature|Coordonnées viticulteur|Volume proposé";
//            $this->description = "Le(s) contrat(s) référencé(s) ci-après restent en attente d’enregistrement d’un prix définitif :";
//            $this->description_fin = "";
//            break;         
//        case AlerteClient::DS_NON_VALIDEE :
//            $this->titre = "Déclaration(s) de stocks manquante(s)";
//            $this->refarticle = "cf Art II-1 Accord interprofessionnel en vigueur";
//            $this->multiple = false;
//            $this->liste_champs = "";
//            $this->description = "Nous vous remercions de nous transmettre votre déclaration de stocks au : ";
//            $this->description_fin = "";
//            break;        
//        case AlerteClient::DRM_STOCK_NEGATIF:
//            $this->multiple = false;
//            $this->titre = "DRM avec un stock négatif";
//            $this->refarticle = " XXXXX ";
//            $this->description = "Nous vous remercions de corriger les volumes des DRM suivantes :";
//            break;
//        case AlerteClient::SV12_MANQUANTE:            
//             $this->multiple = false;
//             $this->titre = "Déclaration de production SV12";
//             $this->refarticle = "cf Art VI-3 Accord interprofessionnel en vigueur";
//             $this->description = "Nous vous demandons de bien vouloir nous faire parvenir une copie de votre déclaration de production (document SV12) faisant suite à vos achats de raisins et moûts sur la campagne en cours.";
//             $this->description_fin = "Ce document doit nous parvenir au plus tard \\textbf{le 15 janvier} de l’année suivant la récolte.";
//           break;
//        case AlerteClient::VRAC_SANS_SV12:
//             $this->multiple = false;
//             $this->titre = "Déclaration de production SV12";
//             $this->refarticle = "cf Art VI-3 Accord interprofessionnel en vigueur";
//             $this->description = "Nous avons enregistré des contrats d’achats de raisins et moûts vous concernant sur la campagne en cours dont les N° d’enregistrements sont les suivants :";
//             $this->description_fin = "Dans ce contexte, nous vous demandons de bien vouloir nous faire parvenir copie votre déclaration de production SV12 dans les meilleurs délais.";
//          break;
//        case AlerteClient::ECART_DREV_DRM:
//             $this->multiple = true;
//             $this->titre = "Volume revendiqués";
//             $this->refarticle = "cf Art II-2 Accord interprofessionnel en vigueur";
//             $this->description = "Nous constatons un écart entre les volumes revendiqués transmis par l’ODG et les volumes revendiqués portés sur vos DRM de la campagne ".$campagne.".";
//             $this->liste_champs = "Appellation|Vol. rev. ODG|Vol. rev. DRM|Ecart";
//             $this->description_fin = "Nous vous remercions de bien vouloir nous contacter afin de nous informer de la raison de cet écart.";
//          break;      
//      case AlerteClient::ECART_DS_DRM_JUILLET:
//             $this->multiple = true;
//             $date = format_date(DSClient::getInstance()->find($idDoc)->date_stock, 'dd/MM/yyyy');
//             $this->titre = "DRM de juillet / Déclaration de stocks";
//             $this->refarticle = "cf Art V-4 Accord interprofessionnel en vigueur";
//             $this->description = "Nous constatons un écart entre le stock final de votre DRM du mois de juillet ".substr($campagne, 5)." et votre Déclaration de stocks au ".$date." : ";
//             $this->liste_champs = "Appellation|DRM juillet|Décl. stocks|Ecart";
//             $this->description_fin = "Nous vous remercions de bien vouloir nous contacter afin de nous informer de la raison de cet écart.";
//          break;
//      case AlerteClient::ECART_DS_DRM_AOUT:
//             $this->multiple = true;
//             $date = format_date(DSClient::getInstance()->find($idDoc)->date_stock, 'dd/MM/yyyy');
//             $this->titre = "Déclaration de stocks / DRM d'août";
//             $this->refarticle = "cf Art V-4 Accord interprofessionnel en vigueur";
//             $this->description = "Nous constatons un écart entre le stock de votre DRM du mois d'août ".substr($campagne, 0,4)." et votre Déclaration de stocks au ".$date." : ";
//             $this->liste_champs = "Appellation|DRM août|Décl. stocks|Ecart";
//             $this->description_fin = "Nous vous remercions de bien vouloir nous contacter afin de nous informer de la raison de cet écart.";
//       break;
        default:
            break;
    }
 }
}